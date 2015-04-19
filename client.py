# echo_client.py 
import socket 
import MySQLdb
import time

host = '192.168.122.101' 
port = 2000



# The same port as used by the server 
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 
s.connect((host, port)) 

while True:
	sending_str = " /"
	try:
		db = MySQLdb.connect(host="127.0.0.1", # your host, usually localhost
		                     user="vibhor", # your username
		                     passwd="vibhor", # your password
		                     db="smartmeter") # name of the data base

		cursor = db.cursor()
		# sql = "SELECT status, priority FROM components order by priority asc"
		sql = "SELECT status, priority FROM components"
		cursor.execute(sql)
		
		# Fetch all the rows in a list of lists.
		results = cursor.fetchall()
		for row in results:
			sending_str+=row[0]# Now print fetched result
		sending_str+=" A"
		print sending_str
		s.sendall(sending_str)
		cursor.close()
		db.close()
	except:
		print "Error: unable to fecth data"
	time.sleep(5)

s.close()
	