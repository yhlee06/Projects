package com.mycompany.admin;

import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Result_System {
    static Base_Frame base;
    static Table_Frame table;
    static Read file = new Read();
    static String[] student_ids;
    static String[] Add_Math_ids;
    static String[] Math_ids;
    static String[] English_ids;
    static String[] Malay_ids;
    static String[] Mandarine_ids;
    static String[] Chemistry_ids;
    static String[] Biology_ids;
    static String[] Physic_ids;
    static String[] History_ids;

    public static void Result_System(){
        base = new Base_Frame("Result System", 1010, 500);
        
        Label_Frame title = new Label_Frame("Result System", 30, 30, 280, 35);
        title.font(true, 25);
        
        Button_Frame process_button = new Button_Frame("Add", 164, 35, 323, 30, e -> select("process"));
        Button_Frame update_button = new Button_Frame("Save Update", 164, 35, 507, 30, e -> select("update"));
        Button_Frame delete_button = new Button_Frame("Delete", 164, 35, 691, 30, e -> select("delete"));
        table = new Table_Frame(930, 350, 55, 80);

        base.add_widget(title);
        base.add_widget(table);
        base.add_widget(process_button);
        base.add_widget(update_button);
        base.add_widget(delete_button);

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
        String[] column = {"ID", "Student", "Add Math", "Math", "English", "Malay", "Mandarine", "Chemistry", "Biology", "Physic", "History", "Level"};
        String[] enrol_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/result.txt");
        String[] student_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");

        String[][] table_data = new String[enrol_data.length][12];
        String[] data;
        String[] student;
        String[] Add_Math;
        String[] Math;
        String[] English;
        String[] Malay;
        String[] Mandarine;
        String[] Chemistry;
        String[] Biology;
        String[] Physic;
        String[] History;

        student_ids = new String[enrol_data.length];
        Add_Math_ids = new String[enrol_data.length];
        Math_ids = new String[enrol_data.length];
        English_ids = new String[enrol_data.length];
        Malay_ids = new String[enrol_data.length];
        Mandarine_ids = new String[enrol_data.length];
        Chemistry_ids = new String[enrol_data.length];
        Biology_ids = new String[enrol_data.length];
        Physic_ids = new String[enrol_data.length];
        History_ids = new String[enrol_data.length];

        for (int row = 0; row < enrol_data.length; row++){
            data = enrol_data[row].split(";");
            table_data[row][0] = data[0];
            
            for (int index = 0; index < student_data.length; index++){
                student = student_data[index].split(";");
                if (student[0].equals(data[0])){
                    table_data[row][1] = student[1];
                    student_ids[row] = data[1];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Add_Math = enrol_data[index].split(";");
                if (Add_Math[1].equals(data[1])){
                    table_data[row][2] = Add_Math[1];
                    Add_Math_ids[row] = data[1];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Math = enrol_data[index].split(";");
                if (Math[2].equals(data[2])){
                    table_data[row][3] = Math[2];
                    Math_ids[row] = data[2];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                English = enrol_data[index].split(";");
                if (English[3].equals(data[3])){
                    table_data[row][4] = English[3];
                    English_ids[row] = data[3];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Malay = enrol_data[index].split(";");
                if (Malay[4].equals(data[4])){
                    table_data[row][5] = Malay[4];
                    Malay_ids[row] = data[4];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Mandarine = enrol_data[index].split(";");
                if (Mandarine[5].equals(data[5])){
                    table_data[row][6] = Mandarine[5];
                    Mandarine_ids[row] = data[5];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Chemistry = enrol_data[index].split(";");
                if (Chemistry[6].equals(data[6])){
                    table_data[row][7] = Chemistry[6];
                    Chemistry_ids[row] = data[6];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Biology = enrol_data[index].split(";");
                if (Biology[7].equals(data[7])){
                    table_data[row][8] = Biology[7];
                    Biology_ids[row] = data[7];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                Physic = enrol_data[index].split(";");
                if (Physic[8].equals(data[8])){
                    table_data[row][9] = Physic[8];
                    Physic_ids[row] = data[8];
                    break;
                }
            }
            for (int index = 0; index < enrol_data.length; index++) {
                History = enrol_data[index].split(";");
                if (History[9].equals(data[9])){
                    table_data[row][10] = History[9];
                    History_ids[row] = data[9];
                    break;
                }
            }
            table_data[row][11] = data[10];
        }

        table.refresh_data(column, table_data);
    }

    private static void select(String selection) {
        switch (selection){
            case "process" :
                base.setVisible(false);
                Process_Result.Process_Result();
                break;
            case "update" :
                Update update_file = new Update();
                String[][] enrol_data = table.get_data(new int[] {0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11});
                update_file.clear_and_update("Advance_Tuition_Centre/src/main/java/com/mycompany/data/result.txt", enrol_data);
                Message_Frame.message_frame("Update Successful", "Successfully saved updates.");
                display_data();
                break;
            case "delete" :
                Delete delete = new Delete();
                String enrol_id = Message_Frame.input_frame("Remove Student", "Please enter ID.");
                if (enrol_id == null || enrol_id.equals("")){
                    break;
                }

                boolean confirm = Message_Frame.confirm_frame("Confirm Deletion", String.format("Do you really want to delete this student (%s)?", enrol_id));
                if (confirm == true){
                    delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/result.txt", enrol_id);
                    Message_Frame.message_frame("Deletion Successful", "Successfully deleted student result.");
                }
                display_data();
                break;
        }
    }

    public static void menu(){
        base.setVisible(true);
        display_data();
    }
}
