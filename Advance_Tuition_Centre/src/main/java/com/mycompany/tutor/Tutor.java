package com.mycompany.tutor;

import com.mycompany.edit.Update;

public class Tutor {
    String user_id;
    String name;
    String nric;
    String email;
    String contact_number;
    String qualification;
    String username;
    String password;

    public Tutor (String user_id, String name, String nric, String email, String contact_number, String qualification, String username, String password) {
        this.user_id = user_id;
        this.name = name;
        this.nric = nric;
        this.email = email;
        this.contact_number = contact_number;
        this.qualification = qualification;
        this.username = username;
        this.password = password;
    }

    public String get(String info){
        switch (info) {
            case "id": 
                return user_id;
            case "name": 
                return name;
            case "nric":    
                return nric;
            case "email": 
                return email;
            case "contact": 
                return contact_number;
            case "qualification": 
                return qualification;
            case "username": 
                return username;
            case "password":    
                return password;
            default: 
                return "";
        }
    }

    public void update(String[] info){
        Update file = new Update();
        String tutor_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor.txt";
        String user_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt";

        if (!info[1].equals(name)){
            file.update_file(tutor_file, user_id, 1, info[1]);
            name = info[1];
        }

        if (!info[2].equals(nric)){
            file.update_file(tutor_file, user_id, 2, info[2]);
            nric = info[2];
        }
        if (!info[3].equals(contact_number)){
            file.update_file(tutor_file, user_id, 3, info[3]);
            contact_number = info[3];
        }

        if (!info[4].equals(email)){
            file.update_file(tutor_file, user_id, 4, info[4]);
            email = info[4];
        }

        if (!info[5].equals(qualification)){
            file.update_file(tutor_file, user_id, 5, info[5]);
            qualification = info[5];
        }

        if (!info[6].equals(username)){
            file.update_file(tutor_file, user_id, 6, info[6]);
            username = info[6];
        }

        if (!info[7].equals(password)){
            file.update_file(tutor_file, user_id, 7, info[7]);
            password = info[7];
        }


    }
}
