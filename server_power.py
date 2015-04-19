# echo_server.py
import socket
import MySQLdb


host = ''
# Symbolic name meaning all available interfaces 
port = 12345


db = MySQLdb.connect(host="127.0.0.1", # your host, usually localhost
                     user="vibhor", # your username
                     passwd="vibhor", # your password
                     db="smartmeter") # name of the data base

cur_power = 0
cursor = db.cursor()

sql = "SELECT * FROM power_log"

try:
   cursor.execute(sql)
   # Fetch all the rows in a list of lists.
   results = cursor.fetchall()
   for row in results:
      power = row[0]
      # Now print fetched result
      print "power=%s" % (power)
except:
   print "Error: unable to fecth data"

# disconnect from server

# Arbitrary non-privileged port 
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 
s.bind((host, port)) 
while True:
   s.listen(1) 
   conn, addr = s.accept()
   print('Connected by', addr)  
   data = conn.recv(1024)
   if not data: 
      print "BREAKING"
      #break 
   else:
      print data
      sql = "UPDATE power_log SET power=%s"
      print sql
      try:
         cursor = db.cursor()
         if 'e' in data:
            data = "0"
         cursor.execute(sql % (float(data)))
         print "Row(s) were updated :" +  str(cursor.rowcount)
         db.commit()
         results = cursor.fetchall()
         print results
         print "HERE"
      except:
         print "Error: unable to update power"

db.close()
conn.close()