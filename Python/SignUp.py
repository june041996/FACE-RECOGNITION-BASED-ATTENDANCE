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

def assure_path_exists(path):
    dir = os.path.dirname(path)
    if not os.path.exists(dir):
        os.makedirs(dir)
        
def TakeImages():

    check_haarcascadefile()
    assure_path_exists("TrainingImage/")
    serial = 0
   
    Id = (txtId.get())
    name = (txtName.get())
    if ((name.isalnum()) and Id.isdecimal()):
         #post user
        url = 'http://localhost/face_recognition/checkStudent.php'
        myobj = {'username': txtName.get(),'mssv': txtId.get()}

        x = requests.post(url, data = myobj)
        if(x.json()==0):
            mess._show(title='Đăng ký', message='Mã số sinh viên đã tồn tại')
        else:
            cam = cv2.VideoCapture(0)
            harcascadePath = "haarcascade_frontalface_default.xml"
            detector = cv2.CascadeClassifier(harcascadePath)
            sampleNum = 0
            while (True):
                ret, img = cam.read()
                gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
                faces = detector.detectMultiScale(gray, 1.3, 5)
                for (x, y, w, h) in faces:
                    cv2.rectangle(img, (x, y), (x + w, y + h), (255, 0, 0), 2)
                    sampleNum = sampleNum + 1
                    cv2.imwrite("TrainingImage\ " + name + "." + Id + '.' + str(sampleNum) + ".jpg",
                                gray[y:y + h, x:x + w])
                    cv2.imshow('Taking Images', img)
                if cv2.waitKey(100) & 0xFF == ord('q'):
                    break
                elif sampleNum > 100:
                    break
            cam.release()
            cv2.destroyAllWindows()
             #post user
            url = 'http://localhost/face_recognition/postStudent.php'
            myobj = {'username': txtName.get(),'mssv': txtId.get()}

            re = requests.post(url, data = myobj)
            if(re.json()==1):
                res = "Đăng ký thành công"
                message.configure(text=res)
            else:
                res = "Đăng ký thất bại"
                message.configure(text=res)   
       
    else:
        if (name.isalnum() == False):
            
            mess._show(title='Đăng ký', message='Tên bao gồm chữ cái, số')
        elif (Id.isdecimal()==False):  
            mess._show(title='Đăng ký', message='Id phải là số')


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


def clear():
    txtId.delete(0, 'end')
    res = "Điền thông tin  >>> Mở camera >>> Lưu "
    message1.configure(text=res)


def clear2():
    txtName.delete(0, 'end')
    res = "Điền thông tin  >>> Mở camera >>> Lưu "
    message1.configure(text=res)



def TrainImages():
    check_haarcascadefile()
    assure_path_exists("TrainingImageLabel/")
    recognizer = cv2.face_LBPHFaceRecognizer.create()
    harcascadePath = "haarcascade_frontalface_default.xml"
    detector = cv2.CascadeClassifier(harcascadePath)
    faces, ID = getImagesAndLabels("TrainingImage")
    try:
        recognizer.train(faces, np.array(ID))
    except:
        mess._show(title='Lưu dữ liệu', message='Chưa có dữ liệu')
        return
    recognizer.save("TrainingImageLabel\Trainner.yml")
    res = "Lưu và train thông tin thành công"
    message1.configure(text=res)
    
window = tk.Tk()
window.geometry("500x500")
#window.resizable(True,False)
window.title("Hệ thống điểm danh")
window.configure(background='#262523')

frame2 = tk.Frame(window, bg="#BDBDBD")
frame2.place(width=500, height=500)

head2 = tk.Label(frame2, text="Đăng ký", fg="black",bg="#BDBDBD",width=36 ,font=('times', 17, ' bold ') )
head2.grid(row=0,column=0)
lbl = tk.Label(frame2, text="MSSV",width=20  ,height=1  ,fg="black"  ,bg="#BDBDBD" ,font=('times', 17, ' bold ') )
lbl.place(x=80, y=55)

txtId = tk.Entry(frame2,width=42 ,fg="black",font=('times', 15, ' bold '))
txtId.place(x=30, y=88)

lbl2 = tk.Label(frame2, text="Tên",width=20  ,fg="black"  ,bg="#BDBDBD" ,font=('times', 17, ' bold '))
lbl2.place(x=80, y=140)

txtName = tk.Entry(frame2,width=42 ,fg="black",font=('times', 15, ' bold ')  )
txtName.place(x=30, y=173)
#32
message1 = tk.Label(frame2, text="Điền thông tin  >>> Mở camera >>> Lưu " ,bg="#BDBDBD" ,fg="black"  ,width=39 ,height=1, activebackground = "yellow" ,font=('times', 15, ' bold '))
message1.place(x=7, y=230)

message = tk.Label(frame2, text="" ,bg="#BDBDBD" ,fg="black"  ,width=39,height=1, activebackground = "yellow" ,font=('times', 16, ' bold '))
message.place(x=7, y=450)

#clearButton = tk.Button(frame2, text="Xóa", command=clear  ,fg="black"  ,bg="#ea2a2a"  ,width=11 ,activebackground = "black" ,font=('times', 11, ' bold '))
#clearButton.place(x=335, y=86)
#clearButton2 = tk.Button(frame2, text="Xóa", command=clear2  ,fg="black"  ,bg="#ea2a2a"  ,width=11 , activebackground = "white" ,font=('times', 11, ' bold '))
#clearButton2.place(x=335, y=172)  

takeImg = tk.Button(frame2, text="Mở camera", command=TakeImages  ,fg="white"  ,bg="blue"  ,width=34  ,height=1, activebackground = "white" ,font=('times', 15, ' bold '))
takeImg.place(x=30, y=300)
trainImg = tk.Button(frame2, text="Lưu dữ liệu", command=TrainImages ,fg="white"  ,bg="blue"  ,width=34  ,height=1, activebackground = "white" ,font=('times', 15, ' bold '))
trainImg.place(x=30, y=380)
window.mainloop()
