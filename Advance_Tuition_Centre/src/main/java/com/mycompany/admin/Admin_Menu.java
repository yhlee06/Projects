package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import com.mycompany.main.*;

public class Admin_Menu {
    static Base_Frame base;
    static Admin user;
    static Read read_file = new Read();

    public static void Admin_Menu(String user_id, String username, String password){
        String[] admins = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/admin.txt");
        String[] temp_user;
        for (int i = 0; i < admins.length; i++){
            temp_user = admins[i].split(";");
            if (temp_user[0].equals(user_id)) {
                user = new Admin(temp_user[0], temp_user[1], temp_user[2], temp_user[3], username, password);
            }
        }

        base = new Base_Frame("Admin Menu", 1168, 492);
        
        Label_Frame welcome = new Label_Frame("Welcome, " + user.get("name"), 22, 10, 572, 61);
        welcome.font(true, 35);
        
        Label_Frame question = new Label_Frame("What are you doing today?", 484, 100, 200, 39);
        question.font(16);
        
        Button_Frame profile = new Button_Frame("PROFILE", 203, 40, 236, 150, e -> select("profile"));
        Button_Frame admin_management = new Button_Frame("ADMIN MANAGEMENT", 203, 40, 536, 150, e -> select("admin"));
        Button_Frame tutor_management = new Button_Frame("TUTOR MANAGEMENT", 203, 40, 836, 150, e -> select("tutor"));
        Button_Frame tutor_assignment = new Button_Frame("TUTOR ASSIGNMENT", 203, 40, 99, 230, e -> select("assign"));
        Button_Frame receptionist_management = new Button_Frame("RECEPTIONIST MANAGEMENT", 223, 40, 329, 230, e -> select("receptionist"));
        Button_Frame monthly_report = new Button_Frame("MONTHLY REPORT", 203, 40, 590, 230, e -> select("report"));
        Button_Frame result_system = new Button_Frame("RESULT SYSTEM", 203, 40, 850, 230, e -> select("result"));
        Button_Frame logout = new Button_Frame("LOG OUT", 100, 40, 22, 400, e -> logout());

        base.add_widget(welcome);
        base.add_widget(question);
        base.add_widget(profile);
        base.add_widget(admin_management);
        base.add_widget(tutor_management);
        base.add_widget(tutor_assignment);
        base.add_widget(receptionist_management);
        base.add_widget(monthly_report);
        base.add_widget(result_system);
        base.add_widget(logout);
        base.setVisible(true);
    }

    public static void logout(){
        base.dispose();
        Main.exit();
    }

    private static void select(String selection){
        base.setVisible(false);
        switch (selection){
            case "profile": Admin_Profile.Admin_Profile(user); break;
            case "admin": Admin_Management.Admin_Management(); break;
            case "tutor": Tutor_Management.Tutor_Management(); break;
            case "assign": Tutor_Assignment.Tutor_Assignment(); break;
            case "receptionist": Receptionist_Management.Receptionist_Management(); break;
            case "report": Monthly_Report.Monthly_Report(); break;
            case "result": Result_System.Result_System(); break;
            
        }
    }

    public static void menu(){
        base.setVisible(true);
    }
}
