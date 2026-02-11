package com.mycompany.tutor;

import com.mycompany.edit.Read;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.main.Main;



public class Tutor_Menu {
    static Base_Frame base;
    static Tutor user;
    static Read read_file = new Read();

        public static void Tutor_Menu(String user_id, String username, String password){
            String[] tutors = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor.txt");
            String[] temp_user;
            for (int i = 0; i < tutors.length;i++){
                temp_user = tutors[i].split(";");
                if (temp_user[0].equals(user_id)){
                    user = new Tutor(temp_user[0],temp_user[1],temp_user[2],temp_user[3],temp_user[4],temp_user[5],username, password); 
                    break;
                }
            }
            base = new Base_Frame("Tutor Menu", 1168, 492);
                    


                /* Welcome text */
            Label_Frame welcome_label = new Label_Frame("Welcome, " + user.get("name")+"!", 435, 100, 572, 61);
            welcome_label.font(true,35);
            Label_Frame question = new Label_Frame("What are we doing today?", 484, 150, 200, 39);
            question.font(16);

                /* Log out & Profile */
            Button_Frame profile_button = new Button_Frame(50, 50, 500, 305, "Advance_Tuition_Centre/src/main/java/com/mycompany/icon/profile.png", 25, 31, e -> select("profile"));
            Button_Frame class_information = new Button_Frame("CLASS INFORMATION", 203, 40, 214, 225, e -> select("class information"));
            Button_Frame class_schedule = new Button_Frame("CLASS SCHEDULE", 203, 40, 734, 225, e -> select("class schedule"));
            Button_Frame view_students = new Button_Frame("VIEW STUDENTS", 203, 40, 474, 225, e -> select("view students"));
            Button_Frame log_out_button = new Button_Frame(50, 50, 590, 305, "Advance_Tuition_Centre/src/main/java/com/mycompany/icon/log_out.png", 26, 26, e -> logout());

            base.add_widget(profile_button);
            base.add_widget(welcome_label);
            base.add_widget(question);
            base.add_widget(log_out_button);
            base.add_widget(class_information);
            base.add_widget(class_schedule);
            base.add_widget(view_students);
  
            base.setVisible(true);
        }

        public static void logout(){
            base.dispose();
            Main.exit();
        }

        public static void select(String selection){
            base.setVisible(false);
            switch (selection){
                case "profile": 
                    Tutor_Profile.Tutor_Profile(user);
                    break;
                case "class information": 
                    Class_Info.Class_Table(user);
                    break;
                case "class schedule":
                    Class_Schedule.Schedule_Table(user);
                    break;
                case "view students":
                    View_Students.Student_Enrollment_Table(user);
                    break;
  
            }
        }
        public static void menu(){
        base.setVisible(true);
    }
}
