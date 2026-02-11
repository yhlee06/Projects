package com.mycompany.student;

import com.mycompany.edit.Read;
import com.mycompany.gui.*;
import com.mycompany.main.Main;

public class Student_Menu {
	
	static Base_Frame base;
    static Student user;
    static Payment user1;
    static Read read_file = new Read();

    public static void Student_Menu(String user_id, String username, String password){
        String[] students = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        String[] temp_user;
        for (int i = 0; i < students.length; i++){
            temp_user = students[i].split(";");
            if (temp_user[0].equals(user_id)) {
                user = new Student(temp_user[0], temp_user[1], temp_user[3], temp_user[4], temp_user[5], username, password);
                break;
            }
        }
        
        String[] user_payment_status = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt");
        String[] temp_user1;
        for (int i = 0; i < user_payment_status.length; i++){
            temp_user1 = user_payment_status[i].split(";");
            if (temp_user1[0].equals(user_id)) {
                user1 = new Payment(temp_user1[0], temp_user1[1], temp_user1[2], temp_user1[3]);
                break;
            }
        }       

        base = new Base_Frame("Student Menu", 1168, 492);
        
        Label_Frame welcome = new Label_Frame("Welcome, " + user.get("name"), 22, 10, 572, 61);
        welcome.font(true, 35);
        
        Label_Frame question = new Label_Frame("What are you doing today?", 484, 100, 200, 39);
        question.font(16);
        
        Button_Frame profile = new Button_Frame("PROFILE", 203, 40, 356, 150, e -> select("profile"));
        Button_Frame class_schedule = new Button_Frame("CLASS SCHEDULE", 203, 40, 609, 150, e -> select("class schedule"));
        Button_Frame payment_status = new Button_Frame("PAYMENT STATUS", 203, 40, 229, 230, e -> select("payment status"));
        Button_Frame payment_proof = new Button_Frame("PAYMENT PROOF", 203, 40, 482, 230, e -> select("payment proof"));
        Button_Frame requests = new Button_Frame("REQUESTS", 203, 40, 736, 230, e -> select("requests"));
        Button_Frame logout = new Button_Frame("LOG OUT", 100, 40, 22, 400, e -> logout());

        base.add_widget(welcome);
        base.add_widget(question);
        base.add_widget(profile);
        base.add_widget(class_schedule);
        base.add_widget(payment_status);
        base.add_widget(payment_proof);
        base.add_widget(requests);
        base.add_widget(logout);
        base.setVisible(true);
    }

    public static void logout(){
        base.dispose();
        Main.exit();
    }

    public static void select(String selection){
        base.setVisible(false);
        switch (selection){
            case "profile": Student_Profile.Student_Profile(user); break;
            case "class schedule": Class_Schedule.Class_Schedule(user); break;
            case "payment status": Payment_Status.Payment_Status(user1); break;
            case "payment proof": Payment_Proof.Payment_Proof(user); break;
            case "requests": Request_Menu.Request_Menu(user); break;
        }
    }

    public static void menu(){
        base.setVisible(true);
    }
}
