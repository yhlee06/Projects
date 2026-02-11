package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Monthly_Report {
    static Base_Frame base;
    static Table_Frame table;
    static Read file = new Read();
    static String[] student_ids;
    static String[] subject_ids;

    public static void Monthly_Report(){
        base = new Base_Frame("Monthly Income Report", 910, 500);
        
        Label_Frame title = new Label_Frame("Monthly Income Report", 30, 30, 280, 35);
        title.font(true, 25);
        
        Button_Frame view_button = new Button_Frame("View", 164, 35, 323, 30, e -> select("View"));
        table = new Table_Frame(800, 350, 55, 80);

        base.add_widget(title);
        base.add_widget(table);
        base.add_widget(view_button);

        display_data();

        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Admin_Menu.menu();
                base.dispose();
            }
        });
    }

    public static void display_data(){
        String[] column = {"Income", "Level", "Subject", "Month"};
        String[] enrol_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");
        String[] income_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt");
        String[] class_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
        
        String[][] table_data = new String[enrol_data.length][5];
        String[] data;
        String[] income;
        String[] subject;

        student_ids = new String[enrol_data.length];
        subject_ids = new String[enrol_data.length];

        for (int row = 0; row < enrol_data.length; row++){
            data = enrol_data[row].split(";");
            for (int index = 0; index < income_data.length; index++){
                income = income_data[index].split(";");
                if (income[0].equals(data[1])){
                    table_data[row][0] = income[1];
                    student_ids[row] = data[1];
                    break;
                }
            }
            for (int index = 0; index < class_data.length; index++) {
                subject = class_data[index].split(";");
                if (subject[0].equals(data[2])){
                    table_data[row][1] = subject[3];
                    subject_ids[row] = data[2];
                    break;
                }
            }
            for (int index = 0; index < class_data.length; index++) {
                subject = class_data[index].split(";");
                if (subject[0].equals(data[2])){
                    table_data[row][2] = subject[2];
                    subject_ids[row] = data[2];
                    break;
                }
            }
            table_data[row][3] = data[3];
        }

        table.refresh_data(column, table_data);
    }

    private static void select(String selection) {
        switch (selection){
            case "view" :
                base.setVisible(false);
                display_data();
                break;
        }
    }

    public static void menu(){
        base.setVisible(true);
    }
}
