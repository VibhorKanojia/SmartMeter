# echo_client.py 
import socket 
import MySQLdb
import time


host = '192.168.1.162' 
port = 2000


# The same port as used by the server 
try:
  s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 
  s.connect((host, port)) 
except:
  print "Unable to create socket"

print "Initializing Biatches"

old_power = -1
old_threshold = -1;

count = 1
while count:
    # count-=1;
    db = MySQLdb.connect(host="127.0.0.1", # your host, usually localhost
                         user="vibhor", # your username
                         passwd="vibhor", # your password
                         db="smartmeter") # name of the data base

    cur_power = 0
    cursor = db.cursor()

    sql = "SELECT * FROM power_log"
    print "Old_power is " + str(old_power)

    try:
       cursor.execute(sql)
       # Fetch all the rows in a list of lists.
       results = cursor.fetchall()
       for row in results:
          cur_power = float(row[0])
          threshold = float(row[1])
    except:
       print "Error: unable to fecth data"
    
    print "Current Power is " + str(cur_power)

    


    print "Evaluating Condition"

    if abs(old_power - cur_power) > 5 or old_power==-1 or old_threshold != threshold:
      print "Alrighty, good to go"
      print "checking the reason"
      print "old_power is " + str(old_power)
      print "cur_power is " + str(cur_power)
      print "#######################################"
      statusArray = []
      copyArray = []
      order = []
      sql = "SELECT * FROM components order by priority asc"
      try:
         cursor.execute(sql)
         # Fetch all the rows in a list of lists.
         results = cursor.fetchall()
         for row in results:
              temp = row[2]
              statusArray.append(temp)
              order.append(int(row[1]))
      except:
         print "Error: unable to fecth data"

      print "status Array and order are:"
      print statusArray
      print order

      print "copyArray is "
      sql = "SELECT * FROM components"
      try:
         cursor.execute(sql)
         # Fetch all the rows in a list of lists.
         results = cursor.fetchall()
         for row in results:
              copyArray.append(row[2])
      except:
         print "Error: unable to fecth data"

      print copyArray

      db.close()
      s.sendall("ASDFG")
      print "trying to send"
          
      i=0
      while i < len(statusArray):
          s.sendall("ASDFG")
          print "trying to send"
          pos = order[i]
          pos-=1
          copyArray[pos] = 'A'
          statusArray[i] = 'A'
          j = 0
          sending_str = " /"
          while (j < len(copyArray)):
              sending_str+=copyArray[j]
              j+=1
          sending_str+=" A"
          
          print "Sending string " + sending_str 
          s.sendall(sending_str)
          print "Sent " + sending_str 
          
          time.sleep(2)


          db = MySQLdb.connect(host="127.0.0.1", # your host, usually localhost
                         user="vibhor", # your username
                         passwd="vibhor", # your password
                         db="smartmeter") # name of the data base

          cursor = db.cursor()
          sql = "SELECT * FROM power_log"

          try:
             cursor.execute(sql)
             # Fetch all the rows in a list of lists.
             results = cursor.fetchall()
             for row in results:
                cur_power = float(row[0])
                threshold = float(row[1])
          except:
             print "Error: unable to fecth data"
          print "After Updating for index " + str(i)
          print "cur_power is " + str(cur_power) + " and threshold is " + str(threshold)
          if cur_power <= threshold:
              statusArray[i] = 'A'
              copyArray[pos] = 'A'
          else:
              statusArray[i] = 'B'
              copyArray[pos] = 'B'
          i+=1
          print "Copy Array after this iteraton is "
          print copyArray
          db.close()
          print "Iteration " + str(i)

      j = 0
      sending_str = " /"
      while (j < len(copyArray)):
          sending_str+=copyArray[j]
          j+=1
      sending_str+=" A"
      print "Final Sending string " + sending_str 
      s.sendall(sending_str)
      
      print "SHHhhhhhhhhh"
      db = MySQLdb.connect(host="127.0.0.1", # your host, usually localhost
                             user="vibhor", # your username
                             passwd="vibhor", # your password
                             db="smartmeter") # name of the data base
      i=0
      while i < len(statusArray):
          cursor = db.cursor()
          sql = "UPDATE components SET status = '" + copyArray[i] + "' where switch_number=" + str(i)
          cursor.execute(sql)
          db.commit()
          i+=1
      db.close()




      time.sleep(5)
      db = MySQLdb.connect(host="127.0.0.1", # your host, usually localhost
                     user="vibhor", # your username
                     passwd="vibhor", # your password
                     db="smartmeter") # name of the data base

      cursor = db.cursor()
      sql = "SELECT * FROM power_log"

      try:
         cursor.execute(sql)
         # Fetch all the rows in a list of lists.
         results = cursor.fetchall()
         for row in results:
            cur_power = float(row[0])
            threshold = float(row[1])
      except:
         print "Error: unable to fecth data"
      db.close()



      old_power = cur_power
      old_threshold = threshold
      print "Updating old_power to " + str(old_power)
      print "**********************************"
      print "Finally copyArray is: "
      print copyArray
      time.sleep(10)