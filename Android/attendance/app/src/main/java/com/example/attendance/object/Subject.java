package com.example.attendance.object;

public class Subject {
    String name;
    String id;
    String time_start;
    String time_end;
    String day_start;
    String day_end;
    String DoW;
    String id_subject;
    String mssv;
    String name_teacher;
    String count;

    public Subject() {
    }

    public Subject(String name, String id, String time_start, String time_end, String day_start, String day_end, String doW, String id_subject, String mssv, String name_teacher,String count) {
        this.name = name;
        this.id = id;
        this.time_start = time_start;
        this.time_end = time_end;
        this.day_start = day_start;
        this.day_end = day_end;
        DoW = doW;
        this.id_subject = id_subject;
        this.mssv = mssv;
        this.name_teacher = name_teacher;
        this.count = count;
    }

    public String getCount() {
        return count;
    }

    public void setCount(String count) {
        this.count = count;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTime_start() {
        return time_start;
    }

    public void setTime_start(String time_start) {
        this.time_start = time_start;
    }

    public String getTime_end() {
        return time_end;
    }

    public void setTime_end(String time_end) {
        this.time_end = time_end;
    }

    public String getDay_start() {
        return day_start;
    }

    public void setDay_start(String day_start) {
        this.day_start = day_start;
    }

    public String getDay_end() {
        return day_end;
    }

    public void setDay_end(String day_end) {
        this.day_end = day_end;
    }

    public String getDoW() {
        return DoW;
    }

    public void setDoW(String doW) {
        DoW = doW;
    }

    public String getMssv() {
        return mssv;
    }

    public void setMssv(String mssv) {
        this.mssv = mssv;
    }

    public String getName_teacher() {
        return name_teacher;
    }

    public void setName_teacher(String name_teacher) {
        this.name_teacher = name_teacher;
    }

    public String getId_subject() {
        return id_subject;
    }

    public void setId_subject(String id_subject) {
        this.id_subject = id_subject;
    }
}
