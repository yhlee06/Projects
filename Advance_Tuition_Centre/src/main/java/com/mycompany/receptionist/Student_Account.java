package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API classes.*/
import com.mycompany.edit.*;
import com.mycompany.gui.Message_Frame;

public class Student_Account extends Details {
    Read_One one_line = new Read_One();

    /**Variables used in the class.*/
    String id;
    String username;
    String password;

    /**Constructor for student account.*/
    public Student_Account(String user_id){
        String[] account = one_line.read(user_id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");

        id = account[0];
        username = account[1];
        password = account[2];
    }

    /**Method to get information.*/
    public String get(String info){
        switch (info){
            case "username" : return username;
            case "password" : return password;
            default : return "";
        }
    }

    /**Method to update information.*/
    public void update(String[] info){
        Update update_file = new Update();
        String file_path = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt";

        Read file = new Read();
        String[] accounts = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
        for (String account : accounts){
            String[] data = account.split(";");
            if (data[1].equals(info[0]) && (!(data[0].equals(id)))){
                Message_Frame.message_frame("Error", "Username already taken. Please choose another one.");
                return;
            }
        }

        if (info[1].length() < 8){
            Message_Frame.message_frame("Error", "Password should be minimum 8 characters long.");
            return;
        }

        if (!(info[0].equals(username))){
            update_file.update_file(file_path, id, 1, info[0]);
            username = info[0];
        }

        if (!(info[1].equals(password))){
            update_file.update_file(file_path, id, 2, info[1]);
            password = info[1];
        }
    }

    /**Method used for searching through the information.*/
    public boolean search(String word){
        boolean flag = false;
        flag = username.equals(word) || password.equals(word);
        return flag;
    }
}
