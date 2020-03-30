#2-3: Imports all the functions and pika client to connect to RabbitMQ
from functions import *
import pika

#5-9: Puts in credentials and connects to RabbitMQ
cred=pika.PlainCredentials('DB', 'DB_1234')

connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.1.11', 5672, '/', cred))
channel = connection.channel()

#12-23: Each queue declared represents a different function the database runs after receiving information
channel.queue_declare(queue='DB_auth', durable=True)
channel.queue_declare(queue='DB_add', durable=True)

channel.queue_declare(queue='DB_ping', durable=True)
channel.queue_declare(queue='DB_recovery', durable=True)

#26: Keeps tracks of the user logged in currently
user=''

#29-38: Takes the username,password and sends the user true or false if they are verified
def auth_request(ch, method, props, body):			
	username = body.split(',')[0]
	password = body.split(',')[1]
	response = auth(username, password)
	#36-38: If the user if verified, stores their username	
	if response=='true':
		global user
		user=username
	ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)

#41-48: Takes the username, password, first name, and last name to add an account
def add_request(ch, method, props, body):
	uName = body.split(',')[0]
	pWord = body.split(',')[1]
	fName = body.split(',')[2]
	lName = body.split(',')[3]
	response = addUser(uName, pWord, fName, lName)
	ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)



#

def ping_request(ch, method, props, body):
	ch.basic_ack(delivery_tag=method.delivery_tag)

def recovery_request(ch, method, props, body):
	recovery(body)
	db_log("Recovery initiated")
	ch.basic_ack(delivery_tag=method.delivery_tag)

#126-142: Begins to listen on each queue and logs that the server has started.
channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='DB_auth', on_message_callback=auth_request)
channel.basic_consume(queue='DB_add', on_message_callback=add_request)

channel.basic_consume(queue='DB_ping', on_message_callback=ping_request)
channel.basic_consume(queue='DB_recovery', on_message_callback=recovery_request)
	
db_log("Database server started")
channel.start_consuming()
