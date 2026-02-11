package com.mycompany.student;

import com.mycompany.edit.*;

public class Student {
    String user_id;
    String name;
    String email;
    String contact_number;
    String address;
    String username;
    String password;

    public Student(String user_id, String name, String email, String contact_number, String address, String username, String password){
        this.user_id = user_id;
        this.name = name;
        this.email = email;
        this.contact_number = contact_number;
        this.address = address;
        this.username = username;
        this.password = password;
    }

    public String get(String info){
        switch (info) {
            case "id": return user_id;
            case "name": return name;
            case "email": return email;
            case "contact": return contact_number;
            case "address": return address;
            case "username": return username;
            case "password": return password;
            default: return "";
        }
    }

    public void update(String[] info){
        Update file = new Update();
        String student_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt";
        String user_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt";

        if (info[1].equals(name) == false){
            file.update_file(student_file, user_id, 1, info[1]);
            name = info[1];
        }

        if (info[2].equals(contact_number) == false){
            file.update_file(student_file, user_id, 4, info[2]);
            contact_number = info[2];
        }

        if (info[3].equals(email) == false){
            file.update_file(student_file, user_id, 3, info[3]);
            email = info[3];
        }
        
        if (info[4].equals(address) == false){
            file.update_file(student_file, user_id, 5, info[4]);
            address = info[4];
        }

        if (info[5].equals(username) == false){
            file.update_file(user_file, user_id, 1, info[5]);
            username = info[5];
        }

        if (info[6].equals(password) == false){
            file.update_file(user_file, user_id, 2, info[6]);
            password = info[6];
        }

    }

}
