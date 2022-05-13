import tkinter as tk
from tkinter import ttk
from tkinter import messagebox as mess
import tkinter.simpledialog as tsd
import cv2,os
import csv
import numpy as np
from PIL import Image
import pandas as pd
import datetime
import time
import mysql.connector
import requests

def check_haarcascadefile():
    exists = os.path.isfile("haarcascade_frontalface_default.xml")
    if exists:
        pass
    else:
        mess._show(title='Thư viện', message='Thiếu thư viện nhận haarcascade')
        window.destroy()

def getImagesAndLabels(path):
    imagePaths = [os.path.join(path, f) for f in os.listdir(path)]

    faces = []
    Ids = []

    for imagePath in imagePaths:
        pilImage = Image.open(imagePath).convert('L')

        imageNp = np.array(pilImage, 'uint8')
        ID = int(os.path.split(imagePath)[-1].split(".")[1])

        faces.append(imageNp)
        Ids.append(ID)
    return faces, Ids

def TrackImages():
    if (cb.get()): 
        check_haarcascadefile()
        for k in tv.get_children():
            tv.delete(k)
        msg = ''
        i = 0
        j = 0
        username=''
        mssv=''
        time=''
        recognizer = cv2.face.LBPHFaceRecognizer_create()  
        #recognizer = cv2.createLBPHFaceRecognizer()
        exists3 = os.path.isfile("TrainingImageLabel\Trainner.yml")
        if exists3:
            recognizer.read("TrainingImageLabel\Trainner.yml")
        else:
            mess._show(title='Sinh viên', message='Chưa train dữ liệu')
            return
        harcascadePath = "haarcascade_frontalface_default.xml"
        faceCascade = cv2.CascadeClassifier(harcascadePath)

        cam = cv2.VideoCapture(0)
        font = cv2.FONT_HERSHEY_SIMPLEX
        mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="attendances"
        )
        mycursor = mydb.cursor()
        while True:
            ret, im = cam.read()
            gray = cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)
            faces = faceCascade.detectMultiScale(gray, 1.2, 5)
            for (x, y, w, h) in faces:
                cv2.rectangle(im, (x, y), (x + w, y + h), (225, 0, 0), 2)
                serial, conf = recognizer.predict(gray[y:y + h, x:x + w])
                if (conf < 40):

                   

                    sql = """SELECT `username` FROM `student` WHERE `mssv` = %s"""

                    mycursor.execute(sql, (serial, ))

                    myresult = mycursor.fetchall()
                    
                    mssv=str(serial)
                    username = myresult[0][0]
                    cv2.putText(im,username+"_"+mssv, (x, y + h), font, 1, (255, 255, 255), 2)
                else:
                    cv2.putText(im,'Unknown', (x, y + h), font, 1, (255, 255, 255), 2)

            cv2.imshow('Nhận dạng khuôn mặt', im)
    #điểm danh tự động        
            # if(username!=''):
            #     break
            if (cv2.waitKey(1) == ord('q')):
                break
        if(username==''):
            mess._show(title='Điểm danh', message='Chưa nhận dạng được gương mặt')
        else:   
            
            #insert attendace
            url1 = 'http://localhost/face_recognition/postAttendance.php'
            myobj = {'mssv': mssv,'course': cb.get(),'date': date,'time':dates}

            x = requests.post(url1, data = myobj)
            print(x.json())
            if x.json()==0:
                mess._show(title='Điểm danh', message='Đã điểm danh trước đó rồi '+username)
            elif x.json()==1:
                mess._show(title='Điểm danh', message='Điểm danh thành công')
            else:
                mess._show(title='Điểm danh', message='Sinh viên ko có trong danh sách lớp')
            #load attendance in scroll
            x = requests.get('http://localhost/face_recognition/getAttendace.php')
            for r in x.json():
                #print(r['id']) 
                tv.insert('', i,text= r['id'], values=(r['mssv'],r['name'],r['username'], r['date'], r['time']))       
            
        cam.release()
        cv2.destroyAllWindows()
        
    else: mess._show(title='Điểm danh', message='Chọn môn học trước khi điểm danh')

def TrackImagesAuto():
    if (cb.get()): 
        check_haarcascadefile()
        for k in tv.get_children():
            tv.delete(k)
        msg = ''
        i = 0
        j = 0
        username=''
        mssv=''
        time=''
        recognizer = cv2.face.LBPHFaceRecognizer_create()  
        #recognizer = cv2.createLBPHFaceRecognizer()
        exists3 = os.path.isfile("TrainingImageLabel\Trainner.yml")
        if exists3:
            recognizer.read("TrainingImageLabel\Trainner.yml")
        else:
            mess._show(title='Sinh viên', message='Chưa train dữ liệu')
            return
        harcascadePath = "haarcascade_frontalface_default.xml"
        faceCascade = cv2.CascadeClassifier(harcascadePath)

        cam = cv2.VideoCapture(0)
        font = cv2.FONT_HERSHEY_SIMPLEX
        mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="attendances"
        )

        mycursor = mydb.cursor()
        while True:
            
            ret, im = cam.read()
            gray = cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)
            faces = faceCascade.detectMultiScale(gray, 1.2, 5)
            for (x, y, w, h) in faces:
                
                cv2.rectangle(im, (x, y), (x + w, y + h), (225, 0, 0), 2)
                serial, conf = recognizer.predict(gray[y:y + h, x:x + w])
                if (conf < 40):
                    
                    

                    sql = """SELECT `username` FROM `student` WHERE `mssv` = %s"""

                    mycursor.execute(sql, (serial, ))

                    myresult = mycursor.fetchall()
                    
                    mssv=str(serial)
                    username = myresult[0][0]
                    cv2.putText(im,username+"_"+mssv, (x, y + h), font, 1, (255, 255, 255), 2)
                     #insert attendace
                    url1 = 'http://localhost/face_recognition/postAttendance.php'
                    myobj = {'mssv': mssv,'course': cb.get(),'date': date,'time':dates}

                    x = requests.post(url1, data = myobj)
                    print(x.json())
                    if x.json()==0:
                        print("Đã điểm danh trước đó rồi: "+username)
                        
                    else:
                        print("Điểm danh thành công: "+username)
                else:
                    cv2.putText(im,'Unknown', (x, y + h), font, 1, (255, 255, 255), 2)
                    print("Không nhận diện được gương mặt")

            cv2.imshow('Nhận dạng khuôn mặt', im)

            if (cv2.waitKey(1) == ord('q')):
                break
        #load attendance in scroll
        x = requests.get('http://localhost/face_recognition/getAttendace.php')
        for r in x.json():
            #print(r['id']) 
            tv.insert('', i,text= r['id'], values=(r['mssv'],r['name'],r['username'], r['date'], r['time']))       
            
        cam.release()
        cv2.destroyAllWindows()
        
    else: mess._show(title='Điểm danh', message='Chọn môn học trước khi điểm danh')

ts = time.time()
date = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d')
dates = datetime.datetime.fromtimestamp(ts).strftime('%H:%M')

window = tk.Tk()
window.geometry("500x500")
#window.resizable(True,False)
window.title("Hệ thống điểm danh")
window.configure(background='#262523')

frame1 = tk.Frame(window, bg="#00aeff")
frame1.place(width=500, height=500)

head1 = tk.Label(frame1, text="Điểm danh", fg="black",bg="#BDBDBD" ,width=36 ,font=('times', 17, ' bold ') )
head1.place(x=0,y=0)

lbl3 = tk.Label(frame1, text="Danh sách sinh viên",width=20  ,fg="black"  ,bg="#00aeff"  ,height=1 ,font=('times', 17, ' bold '))
lbl3.place(x=100, y=115)

trackImg = tk.Button(frame1, text="Điểm danh SV", command=TrackImages  ,fg="white"  ,bg="blue"  ,width=20  ,height=1, activebackground = "white" ,font=('times', 12, ' bold '))
trackImg.place(x=300,y=50)
buttonAuto = tk.Button(frame1, text="Điểm danh nhiều SV", command=TrackImagesAuto  ,fg="white"  ,bg="blue"  ,width=20  ,height=1, activebackground = "white" ,font=('times', 12, ' bold '))
buttonAuto.place(x=300,y=80)
trackImg1 = tk.Label(frame1, text="Môn học" ,fg="black"  ,bg="#00aeff"  ,width=15  ,height=1, activebackground = "white" ,font=('times', 12, ' bold '))
trackImg1.place(x=30,y=50)
#select subject
x = requests.get('http://localhost/face_recognition/getCourse.php')
arr=[]
for r in x.json():
    arr.append(r['name'])

cb=ttk.Combobox(frame1,values=arr)
cb.place(x=30,y=75)

#quitWindow = tk.Button(frame1, text="Thoát", command=window.destroy  ,fg="black"  ,bg="red"  ,width=35 ,height=1, activebackground = "white" ,font=('times', 15, ' bold '))
#quitWindow.place(x=30, y=450)

#treeview

tv= ttk.Treeview(frame1,height =13,columns = ('mssv','name','date','time','course'))
tv.column('#0',width=50)
tv.column('mssv',width=50)
tv.column('name',width=70)
tv.column('date',width=180)
tv.column('time',width=70)
tv.column('course',width=70)

tv.grid(row=2,column=0,padx=(0,0),pady=(150,0),columnspan=6)
tv.heading('#0',text ='ID')
tv.heading('mssv',text ='MSSV')
tv.heading('name',text ='Môn học')
tv.heading('date',text ='Sinh viên')
tv.heading('time',text ='Ngày')
tv.heading('course',text ='Thời gian')

#scroll

scroll=ttk.Scrollbar(frame1,orient='vertical',command=tv.yview)
scroll.grid(row=2,column=6,padx=(0,100),pady=(150,0),sticky='ns')
tv.configure(yscrollcommand=scroll.set)

window.mainloop()
