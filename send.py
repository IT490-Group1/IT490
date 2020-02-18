import pika

def sendToUser(msg):
    # establishes a connection with RabbitMQ server
    connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
    channel = connection.channel()

    # add 'helo' to the queue
    channel.queue_declare(queue='hello')

    # sending the message to the queue_declare

    channel.basic_publish(  exchange='',
                            routing_key = 'hello',
                            body=msg)
    print (" [x] Sent " + message)

    sendToUser("sent from python")
    connection.close()
