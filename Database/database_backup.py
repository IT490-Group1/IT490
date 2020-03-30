#2-3: Imports all the functions and pika client to connect to RabbitMQ
from backup_functions import *
import pika

#5-9: Puts in credentials and connects to RabbitMQ
cred=pika.PlainCredentials('DB', 'DB_1234')

connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.1.11', 5672, '/', cred))
channel = connection.channel()

#12-23: Each queue declared represents a different function the database runs after receiving information
channel.queue_declare(queue='DBB_auth', durable=True)
channel.queue_declare(queue='DBB_add', durable=True)

channel.queue_declare(queue='DB_ping', durable=True)

#26: Keeps tracks of the user logged in currently
user=''
disaster=False
recovery=""

#29-38: Takes the username,password and sends the user true or false if they are verified
def auth_request(ch, method, props, body):			
	username = body.split(',')[0]
	password = body.split(',')[1]
	response = auth_DBB(username, password)
	#36-38: If the user if verified, stores their username	
	if response=='true':
		global user
		user=username
	if disaster==True:
		ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)

#41-48: Takes the username, password, first name, and last name to add an account
def add_request(ch, method, props, body):
	uName = body.split(',')[0]
	pWord = body.split(',')[1]
	fName = body.split(',')[2]
	lName = body.split(',')[3]
	response = addUser_DBB(uName, pWord, fName, lName, disaster)
	if disaster==True:
		global recovery
		if response=="false":
			ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response))
		else:
			recovery=recovery+"END"+response.split("END")[1]
			ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response.split("END")[0]))
	ch.basic_ack(delivery_tag=method.delivery_tag)




def ack(ch, method, props, body):
    ch.basic_ack(delivery_tag=method.delivery_tag)

def backup_request(ch, method, props, body):
	global disaster	
	global recovery
	if body=="true":
		disaster=True
		channel.basic_consume(queue='DB_auth', on_message_callback=ack, consumer_tag='DB_auth')
		channel.basic_consume(queue='DB_add', on_message_callback=ack, consumer_tag='DB_add')
		
		channel.basic_consume(queue='DB_ping', on_message_callback=ack, consumer_tag='DB_ping')
		recovery=""
		db_log("Database failed, starting disaster mode")
	else:	
		disaster=False
		channel.basic_cancel(consumer_tag='DB_auth')
		channel.basic_cancel(consumer_tag='DB_add')
		
		channel.basic_cancel(consumer_tag='DB_ping')
		channel.basic_cancel(consumer_tag='DB_recovery')
		db_log("Database back online, forprintwarding queries")
		channel.exchange_declare(exchange='DB_recovery', exchange_type='direct', durable=True)
		channel.basic_publish(exchange='DB_recovery', routing_key='', body=recovery)
	ch.basic_ack(delivery_tag=method.delivery_tag)

#126-142: Begins to listen on each queue and logs that the server has started.
channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='DBB_auth', on_message_callback=auth_request)
channel.basic_consume(queue='DBB_add', on_message_callback=add_request)

channel.basic_consume(queue='DBB_backup', on_message_callback=backup_request)
	
db_log("Backup database server started")
channel.start_consuming()
