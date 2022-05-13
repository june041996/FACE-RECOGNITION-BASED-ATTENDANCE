############################################# IMPORTING ################################################
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

############################################# FUNCTIONS ################################################
# lấy đường dẫn thư mục
def assure_path_exists(path):
    dir = os.path.dirname(path)
    if not os.path.exists(dir):
        os.makedirs(dir)

##################################################################################

def tick():
    time_string = time.strftime('%H:%M:%S')
    clock.config(text=time_string)
    clock.after(200,tick)

###################################################################################

def contact():
    mess._show(title='Liên hệ', message="Liên hệ : 'dhphuc.18it5@vku.udn.vn' ")

###################################################################################

def check_haarcascadefile():
    exists = os.path.isfile("haarcascade_frontalface_default.xml")
    if exists:
        pass
    else:
        mess._show(title='Thư viện', message='Thiếu thư viện nhận haarcascade')
        window.destroy()




#####################################################################################

def psw():
    password = tsd.askstring('Password', 'Enter Password', show='*')
    url = 'http://localhost/face_recognition/checkPass.php'
    myobj = {'password': password}

    x = requests.post(url, data = myobj)
    if(x.json()==1):
        TrainImages()
    else:
        mess._show(title='Nhập mật khẩu', message='Sai mật khẩu, dữ liệu chưa được huấn luyện')    
    
   

######################################################################################

def clear():
    txtId.delete(0, 'end')
    res = "Điền thông tin  >>> Mở camera >>> Lưu e"
    message1.configure(text=res)


def clear2():
    txtName.delete(0, 'end')
    res = "Điền thông tin  >>> Mở camera >>> Lưu "
    message1.configure(text=res)

#######################################################################################
#chup anh

        
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

####################################Lưu và train################################################

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

#####################################Lấy mssv và array img###################################################3

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

###########################################nhận diện################################################

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
        database="face_regognition"
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
            else:
                mess._show(title='Điểm danh', message='Điểm danh thành công')
            
            #load attendance in scroll
            x = requests.get('http://localhost/face_recognition/getAttendace.php')
            for r in x.json():
                #print(r['id']) 
                tv.insert('', i,text= r['id'], values=(r['mssv'],r['subject'],r['username'], r['date'], r['time']))       
            
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
        database="face_regognition"
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
            tv.insert('', i,text= r['id'], values=(r['mssv'],r['subject'],r['username'], r['date'], r['time']))       
            
        cam.release()
        cv2.destroyAllWindows()
        
    else: mess._show(title='Điểm danh', message='Chọn môn học trước khi điểm danh')
######################################## lấy time ############################################
    
global key
key = ''

ts = time.time()
date = datetime.datetime.fromtimestamp(ts).strftime('%d-%m-%Y')
dates = datetime.datetime.fromtimestamp(ts).strftime('%H:%M:%S')

day,month,year=date.split("-")

mont={'01':'January',
      '02':'February',
      '03':'March',
      '04':'April',
      '05':'May',
      '06':'June',
      '07':'July',
      '08':'August',
      '09':'September',
      '10':'October',
      '11':'November',
      '12':'December'
      }

######################################## WINDOW ###########################################

window = tk.Tk()
window.geometry("1280x720")
window.resizable(True,False)
window.title("Hệ thống điểm danh")
window.configure(background='#262523')
messageTitle = tk.Label(window, text="Điểm danh nhận diện khuôn mặt" ,fg="white",bg="#262523" ,width=55 ,height=1,font=('times', 29, ' bold '))
messageTitle.place(x=10, y=10)
#menu
menubar = tk.Menu(window,relief='ridge')
filemenu = tk.Menu(menubar,tearoff=0)

filemenu.add_command(label='Liên hệ', command = contact)
filemenu.add_command(label='Thoát',command = window.destroy)
menubar.add_cascade(label='Menu',font=('times', 29, ' bold '),menu=filemenu)
window.configure(menu=menubar)


#Hiện date
frame4 = tk.Frame(window, bg="#c4c6ce")
frame4.place(relx=0.36, rely=0.09, relwidth=0.16, relheight=0.07)

datef = tk.Label(frame4, text = day+"-"+mont[month]+"-"+year+"  |  ", fg="white",bg="#262523" ,width=55 ,height=1,font=('times', 22, ' bold '))
datef.pack(fill='both',expand=1)
#hiện time
frame3 = tk.Frame(window, bg="white")
frame3.place(relx=0.52, rely=0.09, relwidth=0.09, relheight=0.07)

clock = tk.Label(frame3,fg="white",bg="#262523" ,width=55 ,height=1,font=('times', 22, ' bold '))
clock.pack(fill='both',expand=1)
tick()
######################################## FRAME 1 ###########################################
frame1 = tk.Frame(window, bg="#00aeff")
frame1.place(relx=0.11, rely=0.17, relwidth=0.39, relheight=0.80)

head1 = tk.Label(frame1, text="Điểm danh", fg="black",bg="#BDBDBD" ,width=36 ,font=('times', 17, ' bold ') )
head1.place(x=0,y=0)

lbl3 = tk.Label(frame1, text="Danh sách sinh viên",width=20  ,fg="black"  ,bg="#00aeff"  ,height=1 ,font=('times', 17, ' bold '))
lbl3.place(x=100, y=115)

trackImg = tk.Button(frame1, text="Điểm danh 1 SV", command=TrackImages  ,fg="white"  ,bg="blue"  ,width=20  ,height=1, activebackground = "white" ,font=('times', 12, ' bold '))
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

quitWindow = tk.Button(frame1, text="Thoát", command=window.destroy  ,fg="black"  ,bg="red"  ,width=35 ,height=1, activebackground = "white" ,font=('times', 15, ' bold '))
quitWindow.place(x=30, y=450)

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

######################################## FRAME2 ###########################################
frame2 = tk.Frame(window, bg="#00aeff")
frame2.place(relx=0.51, rely=0.17, relwidth=0.38, relheight=0.80)

head2 = tk.Label(frame2, text="Đăng ký", fg="black",bg="#BDBDBD",width=36 ,font=('times', 17, ' bold ') )
head2.grid(row=0,column=0)
lbl = tk.Label(frame2, text="MSSV",width=20  ,height=1  ,fg="black"  ,bg="#00aeff" ,font=('times', 17, ' bold ') )
lbl.place(x=80, y=55)

txtId = tk.Entry(frame2,width=32 ,fg="black",font=('times', 15, ' bold '))
txtId.place(x=30, y=88)

lbl2 = tk.Label(frame2, text="Tên",width=20  ,fg="black"  ,bg="#00aeff" ,font=('times', 17, ' bold '))
lbl2.place(x=80, y=140)

txtName = tk.Entry(frame2,width=32 ,fg="black",font=('times', 15, ' bold ')  )
txtName.place(x=30, y=173)

message1 = tk.Label(frame2, text="Điền thông tin  >>> Mở camera >>> Lưu " ,bg="#00aeff" ,fg="black"  ,width=39 ,height=1, activebackground = "yellow" ,font=('times', 15, ' bold '))
message1.place(x=7, y=230)

message = tk.Label(frame2, text="" ,bg="#00aeff" ,fg="black"  ,width=39,height=1, activebackground = "yellow" ,font=('times', 16, ' bold '))
message.place(x=7, y=450)

clearButton = tk.Button(frame2, text="Xóa", command=clear  ,fg="black"  ,bg="#ea2a2a"  ,width=11 ,activebackground = "black" ,font=('times', 11, ' bold '))
clearButton.place(x=335, y=86)
clearButton2 = tk.Button(frame2, text="Xóa", command=clear2  ,fg="black"  ,bg="#ea2a2a"  ,width=11 , activebackground = "white" ,font=('times', 11, ' bold '))
clearButton2.place(x=335, y=172)  

takeImg = tk.Button(frame2, text="Mở camera", command=TakeImages  ,fg="white"  ,bg="blue"  ,width=34  ,height=1, activebackground = "white" ,font=('times', 15, ' bold '))
takeImg.place(x=30, y=300)
trainImg = tk.Button(frame2, text="Lưu dữ liệu", command=psw ,fg="white"  ,bg="blue"  ,width=34  ,height=1, activebackground = "white" ,font=('times', 15, ' bold '))
trainImg.place(x=30, y=380)


######################################## END ###########################################
window.mainloop()
