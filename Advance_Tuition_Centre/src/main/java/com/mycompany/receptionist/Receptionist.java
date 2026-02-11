package com.mycompany.receptionist; /**Package containing the class.*/

/**Import classes for edit purposes.*/
import com.mycompany.edit.*;

public class Receptionist {
    /**Declare variables used to store the receptionist information.*/
    String user_id;
    String name;
    String contact_number;
    String email;
    String username;
    String password;

    /**Constructor*/
    public Receptionist(String id, String username, String password){
        Read_One read = new Read_One();
        String[] data = read.read(id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist.txt");

        user_id = id;
        name = data[1];
        contact_number = data[2];
        email = data[3];
        this.username = username;
        this.password = password;
    }

    /**Method to get information about receptionist.*/
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

    /**Method to update the receptionist information in text file.*/
    public void update(String[] info){
        Update file = new Update();
        String receptionist_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist.txt";
        String user_file = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt";

        if (!(info[1].equals(name))){
            file.update_file(receptionist_file, user_id, 1, info[1]);
            name = info[1];
        }

        if (!(info[2].equals(contact_number))){
            file.update_file(receptionist_file, user_id, 2, info[2]);
            contact_number = info[2];
        }

        if (!(info[3].equals(email))){
            file.update_file(receptionist_file, user_id, 3, info[3]);
            email = info[3];
        }

        if (!(info[4].equals(username))){
            file.update_file(user_file, user_id, 1, info[4]);
            username = info[4];
        }

        if (!(info[5].equals(password))){
            file.update_file(user_file, user_id, 2, info[5]);
            password = info[5];
        }
    }
}