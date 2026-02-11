package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and methods.*/
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;
import javax.swing.JFrame;
import com.mycompany.edit.*;
import com.mycompany.gui.*;

public class Student_Management {
    /**Declare variables used in the class.*/
    static Base_Frame base;
    static Input_Frame search_input;
    static Table_Frame student_table;
    static Read file = new Read();
    static Read_One one_line = new Read_One();

    /**Main menu for student management section.*/
    public static void Student_Management(){
        /**Creates the base.*/
        base = new Base_Frame("Student Management", 575, 570);

        /**Create the title label.*/
        Label_Frame title = new Label_Frame("Student Management", 20, 20, 263, 39);
        title.font(true, 20);
        title.custom_design("#FFFFFF", "#0070C0");
        base.add_widget(title);

        /**Create the search bar.*/
        search_input = new Input_Frame(200, 26, 20, 70);
        base.add_widget(search_input);
        Button_Frame search_button = new Button_Frame("Search", 85, 26, 225, 70, e -> search_data());
        search_button.custom_design("#C1E5F5", "#000000");
        base.add_widget(search_button);

        /**Create the table for displaying all the students.*/
        student_table = new Table_Frame(520, 400, 20, 110);
        student_table.table.getSelectionModel().addListSelectionListener(e -> {
            if (!e.getValueIsAdjusting()){
                int row = student_table.table.getSelectedRow();
                if (row != -1){
                    base.setVisible(false);
                    String[][] table_data = student_table.get_data(new int[] {0});
                    Student_Details.Student_Details(table_data[row][0]);
                }
            }
        });
        base.add_widget(student_table);
        display_data();

        /**Create the add, update, delete buttons.*/
        Button_Frame add_button = new Button_Frame("Register", 85, 26, 365, 70, e -> select("register"));
        add_button.custom_design("#D9F2D0", "#000000");
        base.add_widget(add_button);
        Button_Frame refresh_button = new Button_Frame("Refresh", 85, 26, 455, 70, e -> select("refresh"));
        refresh_button.custom_design("#FFFFCC", "#000000");
        base.add_widget(refresh_button);

        /**Makes the window visible and also links this to the main menu when closed.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Receptionist_Menu.menu();
                base.dispose();
            }
        });
    }

    /**Method to display data in the table.*/
    private static void display_data(){
        String[] column = {"ID", "Name", "Level"};
        String[] data_file = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        String[][] table_data = new String[data_file.length][3];

        for (int i = 0; i < data_file.length; i++){
            String[] data = data_file[i].split(";");
            String[] user = one_line.read(data[0], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
            table_data[i][0] = data[0];
            table_data[i][1] = data[1];
            table_data[i][2] = data[6];
        }

        student_table.refresh_data(column, table_data);
        student_table.lock_data();
    }

    /**Method to filter through data based on search input.*/
    private static void search_data(){
        ArrayList<String> filter = new ArrayList<>();
        String[] student_data = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        for (String current : student_data){
            String[] data = current.split(";");
            Profile student = new Profile(data[0]);
            String[] words = search_input.get_input().split(" ");
            for (String word : words){
                boolean test = student.search(word);
                if (test == true && !(filter.contains(data[0]))){
                    filter.add(data[0]);
                    break;
                }
            }
        }

        String[] filtered = filter.toArray(new String[0]);
        String[] column = {"ID", "Name", "Level"};
        String[][] table_data = new String[filtered.length][3];

        for (int i = 0; i < filtered.length; i++){
            String[] user = one_line.read(filtered[i], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            table_data[i][0] = user[0];
            table_data[i][1] = user[1];
            table_data[i][2] = user[6];
        }

        student_table.refresh_data(column, table_data);
        student_table.lock_data();
    }

    /**Method for the button functionality.*/
    private static void select(String selection){
        switch (selection) {
            case "register" : 
                base.setVisible(false);
                Student_Registration.Student_Registration();
                break;
            case "refresh" : 
                String[] payments = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt");
                for (String payment : payments){
                    String[] payment_data = payment.split(";");
                    String[] check_student = one_line.read(payment_data[0], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
                    if (check_student == null){
                        return;
                    }
                    Profile student = new Profile(payment_data[0]);
                    student.refresh_payment();
                }
                break;
        }
    }
    
    /**Method for when the window needs to be visible after visiting a different section.*/
    public static void menu(){
        base.setVisible(true);
        display_data();
    }
}
