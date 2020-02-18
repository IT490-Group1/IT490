import pika
import requests
import json

BACKEND_IP = '10.255.46.169'

# establishes a connection with RabbitMQ server
connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
channel = connection.channel()

# Make sure the queue exists - there will only ever be 1 queue, so just run the commaand to declare a queue
channel.queue_declare(queue='hello')

def callback(ch, method, properties, body):
    print(" [x] Received %r" % body)
    print(type(list(body)))
    print(list(body))
    body=body.decode("utf-8")

    searchParameter = body.split("|")[0]
    searchTerm      = body.split("|")[1]

    searchTerm.replace(" ", "+")
    query = "https://api.fda.gov/drug/label.json?search=" + searchParameter + ":" + searchTerm

    responseJSON = requests.get(query)
    print(responseJSON.text)
    responseDict = json.loads(responseJSON.text)

channel.basic_consume(queue='hello',
                      auto_ack=True,
                      on_message_callback=callback)

print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()
