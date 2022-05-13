import requests
from tkinter import messagebox as mess

# x = requests.get('http://localhost/face_recognition/getAttendace.php')
# for r in x.json():
#   print(r['id'])

url = 'http://localhost/face_recognition/postData.php'
myobj = {'user': 'somevalue1','course': 'somevalue','date': 'somevalue'}

x = requests.post(url, data = myobj)
print(x.json())
if x.json()==0:
  mess._show(title='Wrong Password', message='You have entered wrong password')
else:
  mess._show(title='Wrong Password', message='ok')
