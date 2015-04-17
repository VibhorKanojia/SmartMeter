# echo_client.py 
import socket 

host = '192.168.122.102' 
port = 2000



# The same port as used by the server 
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 
s.connect((host, port)) 
s.sendall(b' /BAA A') 
data = s.recv(1024) 
s.close() 
print('Received', repr(data))