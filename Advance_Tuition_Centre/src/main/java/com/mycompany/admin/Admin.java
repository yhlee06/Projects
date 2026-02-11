package com.mycompany.admin;

import com.mycompany.edit.*;

public class Admin {
    String user_id;
    String name;
    String contact_number;
    String email;
    String username;
    String password;

    public Admin(String user_id, String name, String contact_number, String email, String username, String password){
        this.user_id = user_id;
        this.name = name;
        this.contact_number = contact_number;
        this.email = email;
        this.username = username;
        this.password = password;
    }

    public String get(String info){
        switch (info) {
            case "id": return user_id;
            case "name": return name;
            case "contact": return contact_number;
            case "email": return email;
            case "username": return username;
            case "password": return password;
            default: return "";
        }
    }

    public void update(String[] info){
        Update file = new Update();
        String admin_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/admin.txt";
        String user_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt";

        if (info[1].equals(name) == false){
            file.update_file(admin_file, user_id, 1, info[1]);
            name = info[1];
        }

        if (info[2].equals(contact_number) == false){
            file.update_file(admin_file, user_id, 2, info[2]);
            contact_number = info[2];
        }

        if (info[3].equals(email) == false){
            file.update_file(admin_file, user_id, 3, info[3]);
            email = info[3];
        }

        if (info[4].equals(username) == false){
            file.update_file(user_file, user_id, 1, info[4]);
            email = info[4];
        }

        if (info[5].equals(password) == false){
            file.update_file(user_file, user_id, 2, info[5]);
            email = info[5];
        }
    }
}
