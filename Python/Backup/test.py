from tkinter import *
from tkinter.ttk import Combobox
from tkinter import messagebox
import mysql.connector
import os
import requests

class Test:
    def __init__(self, tk):
        self.var = StringVar()
        mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="face_regognition"
        )

        mycursor = mydb.cursor()

        mycursor.execute("SELECT name FROM courses")

        myresult = mycursor.fetchall()

      
        x = requests.get('http://localhost/face_recognition/getCourse.php')
        arr=[]
        for r in x.json():
            arr.append(r['name'])
          
        self.data = arr
        

        self.frame1= Frame(tk, bg="#00aeff").place(relx=0.11, rely=0.17, relwidth=0.39, relheight=0.80)
        head1 = Label(self.frame1, text="                       For Already Registered                       ", fg="black",bg="#3ece48" ,font=('times', 17, ' bold ') )
        head1.place(x=0,y=0)
        lbl3 = Label(self.frame1, text="Attendance",width=20  ,fg="black"  ,bg="#00aeff"  ,height=1 ,font=('times', 17, ' bold '))
        lbl3.place(x=100, y=115)

        self.cb = Combobox(self.frame1, values=self.data)
        self.cb.place(x=60, y=150)
        self.b1 = Button(self.frame1, text="Check", command=self.select).place(x=60, y=300)
    def select(self):
        value = self.cb.get()
        mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="face_regognition"
        )

        mycursor = mydb.cursor()

        
        directory = "GeeksforGeeks/"
  
        # Parent Directory path
        parent_dir = "test/"
        
        # Path
        path = os.path.join(parent_dir, directory)
        dir = os.path.dirname(path)
        print(dir)
        if not os.path.exists(dir):
             os.makedirs(dir)
        
        
        # Create the directory
        # 'GeeksForGeeks' in
        # '/home / User / Documents'
        

tk = Tk()
tk.geometry("600x500")
tk.title("Xiith.com")
tt = Test(tk)
tk.mainloop()