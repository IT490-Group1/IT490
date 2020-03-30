#2: Python mssql library for running queries
import pyodbc
import pika
import time

#7-17: Connecting to Database
details = {
 'server' : 'localhost',
 'database' : 'projectDB',
 'username' : 'tsagan',
 'password' : 'tsagan',
 'connection' : 'no'
 }

connect_string = 'DRIVER={{ODBC Driver 17 for SQL Server}};SERVER={server};PORT=1443; DATABASE={database};UID={username};PWD={password};Trusted_connection={connection};)'.format(**details)

connection = pyodbc.connect(connect_string)

#20: Cursor runs queries and fetches rows
cursor = connection.cursor()

#23-40: db_log writes a log to a local file both on the system and in a master file 
def db_log(message):
	#25-29: Writes log to local file
	stamp = time.asctime( time.localtime(time.time()))
	message = stamp + " -> " + message
	
	with open('./server_log', 'a') as log_file:
		log_file.write(message+"\n")
		
	#32-40: Sends message to DB for logging
	cred = pika.PlainCredentials('RMQ','RMQ_1234')

	connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.1.11', 5672, '/', cred))
	channel = connection.channel()
	
	channel.exchange_declare(exchange='DB_logs', exchange_type='direct', durable=True)
	channel.basic_publish(exchange='DB_logs', routing_key='', body=message)

	connection.close()
	print (message)

#44-59: Auth function used to see if a user's passwords match up
def auth_DBB(uName, pWord):
	query="SELECT password FROM accounts WHERE username='"+uName+"'"
	with cursor.execute(query):
		row=cursor.fetchone()
	try: 
		password=row[0]
		if pWord==password:
			db_log(uName+" logged in")			
			return "true"
		else:
			db_log("User failed to log in")				
			return "false"
	#56-58: If a username doesn't exist in the database, you get a type error if you try to put row[0] in a variable.	
	except TypeError:
		db_log("User failed to log in")			
		return "false"

#62-79: addUser function adds a user's credentials into the database
def addUser_DBB(username, password, fName, lName, mode):	
	#64-67: Gets a count of the rows and adds one in order to create a primary key for the table
	query1="SELECT COUNT(*) FROM accounts"
	with cursor.execute(query1):
		row=cursor.fetchone()
	count=row[0]+1
	
	#70-79: Inserts the user's row into the database
	query2="INSERT INTO accounts VALUES ("+str(count)+",'"+username+"','"+password+"','"+fName+"','"+lName+"','',0,0,0,0,0,0,0,',,',',,')"
	try:
		cursor.execute(query2)
		connection.commit()
		db_log("User "+username+" was created")	
		if mode==True:	
			return "trueEND"+query2
		else:
			return "true"
	#77-79: If a username is already taken, it gives an intergrity error
	except pyodbc.IntegrityError:		
		db_log("Failed to create new user")			
		return "false"


