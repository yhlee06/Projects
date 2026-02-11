package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and classes needed.*/
import com.mycompany.gui.*;
import com.mycompany.edit.*;
import com.mycompany.main.Main;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.util.ArrayList;

public class Receptionist_Menu {
    /**Objects used in the class.*/
    static Base_Frame base;
    static Receptionist user;
    static Table_Frame request_table;
    static TextArea_Frame notepad;
    static Label_Frame total_student;
    static Label_Frame total_pending;
    static Read_One one_line = new Read_One();

    public static void Receptionist_Menu(String user_id, String username, String password){
        /**Creates a user object. Based on who logged in.*/
        user = new Receptionist(user_id, username, password);

        /**Creates the base window.*/
        base = new Base_Frame("Main Menu", 575, 570);

        /**Creates the welcome label.*/
        Label_Frame welome_label = new Label_Frame(String.format("Welcome %s", user.get("name")), 227, 70, 120, 24);
        welome_label.font(true, 16);
        base.add_widget(welome_label);

        /**Creates button related to profile and log out.*/
        Button_Frame profile_button = new Button_Frame(36, 36, 504, 20, "Advance_Tuition_Centre/src/main/java/com/mycompany/icon/profile.png", 25, 31, e -> select("profile"));
        base.add_widget(profile_button);
        Button_Frame log_out_button = new Button_Frame(36, 36, 465, 20, "Advance_Tuition_Centre/src/main/java/com/mycompany/icon/log_out.png", 26, 26, e -> logout());
        base.add_widget(log_out_button);

        /**Creates button related to navigations.*/
        Button_Frame student_management_button = new Button_Frame(80, 80, 20, 130, "Advance_Tuition_Centre/src/main/java/com/mycompany/icon/student_management.png", 70, 57, e -> select("student"));
        student_management_button.custom_design("#DCEAF7", "#000000");
        base.add_widget(student_management_button);
        Button_Frame student_request_button = new Button_Frame(80, 80, 300, 130, "Advance_Tuition_Centre/src/main/java/com/mycompany/icon/request.png", 70, 57,  e -> select("requests"));
        student_request_button.custom_design("#DCEAF7", "#000000");
        base.add_widget(student_request_button);

        /**Creates label for the navigation buttons.*/
        /**Student Management Button*/
        Label_Frame student_management_label = new Label_Frame("Student Management", 105, 130, 150, 20);
        student_management_label.font(true, 14);
        base.add_widget(student_management_label);
        total_student = new Label_Frame("Total Active Student : ", 105, 150, 150, 20);
        total_student.font(12);
        base.add_widget(total_student);
        /**Request / Payments Button*/
        Label_Frame student_request_label = new Label_Frame("Request / Payments", 385, 130, 150, 20);
        student_request_label.font(true, 14);
        base.add_widget(student_request_label);
        total_pending = new Label_Frame("Total Pending : ", 385, 150, 150, 20);
        total_pending.font(12);
        base.add_widget(total_pending);

        /**Creates button for switching tabs.*/
        Button_Frame request_tab_button = new Button_Frame("Requests", 101, 27, 20, 250, e -> switch_tab("requests"));
        request_tab_button.custom_design("#FEA8A8", "#000000");
        base.add_widget(request_tab_button);
        Button_Frame notepad_tab_button = new Button_Frame("Notes", 101, 27, 121, 250, e -> switch_tab("notepad"));
        notepad_tab_button.custom_design("#FAEBAA", "#000000");
        base.add_widget(notepad_tab_button);

        /**Creates the table containing the requests pending.*/
        request_table = new Table_Frame(520, 240, 20, 276);
        display_data();
        base.add_widget(request_table);

        /**Creates the text area used as a notepad.*/
        notepad = new TextArea_Frame(520, 240, 20, 276);
        notepad.text_area.addKeyListener(new KeyAdapter() { /**Auto save any updates done to the notepad.*/
            public void keyReleased(KeyEvent e){
                save_notes();
            }
        });
        /**Load any notes if have.*/
        String[] file = one_line.read(user.get("id"), "Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist_notes.txt");
        String notes;
        if (file == null){
            notes = "";
        } else {
            notes = file[1].replaceFirst(" ", "");
            notes = notes.replace("\\n", "\n");
        }
        notepad.set_text(notes);
        notepad.setVisible(false);
        base.add_widget(notepad);

        /**Makes the user interface visible for interaction.*/
        base.setVisible(true);
    }

    /**Method to log out.*/
    private static void logout(){
        base.dispose();
        Main.exit();
    }

    /**Method to make the main menu visible after closing a section.*/
    public static void menu(){
        base.setVisible(true);
        display_data();
    }

    /**Method for navigation and linking to other sections.*/
    private static void select(String selection){
        base.setVisible(false);
        switch (selection){
            case "profile": Receptionist_Profile.Receptionist_Profile(user); break;
            case "student": Student_Management.Student_Management(); break;
            case "requests": Student_Request.Student_Request(); break;
        }
    }

    /**Method for switching between requests table and notepad tab.*/
    private static void switch_tab(String selection){
        switch (selection){
            case "requests": 
                request_table.setVisible(true);
                notepad.setVisible(false);
                break;
            case "notepad":
                request_table.setVisible(false);
                notepad.setVisible(true);
                break;
        }
    }

    /**Automatically save the notes in notepad.*/
    private static void save_notes(){
        String current_notes = notepad.get_input().replace("\n", "\\n");
        String notes = " " + current_notes;
        Add add = new Add();
        Update update = new Update();
        if (one_line.read(user.get("id"), "Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist_notes.txt") == null){
            add.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist_notes.txt", String.format("%s;%s", user.get("id"), notes));
        } else{
            update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/receptionist_notes.txt", user.get("id"), 1, notes);
        }
    }

    /**Display data in the table.*/
    private static void display_data(){
        Read read = new Read(); /**Object for reading all the information in a text file.*/

        /**Retrieves all information about requests and payments sent by students.*/
        String[] requests = read.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt");
        String[] payments = read.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt");

        /**Temporary variable used to store an unknown length of data.*/
        ArrayList<String> temp_list = new ArrayList<String>();

        /**Filters the requests and only find the ones that are pending.*/
        for (int index = 0; index < requests.length; index++){
            String[] current = requests[index].split(";");
            if (current[5].equals("pending")){
                temp_list.add(String.format("%s;%s;%s", current[3], current[1], current[2]));
            }
        }

        /**Filters the payment proofs and only find the ones that are pending.*/
        for (int index = 0; index < payments.length; index++){
            String[] current = payments[index].split(";");
            if (current[6].equals("pending")){
                temp_list.add(String.format("%s;%s;%s", current[4], current[1], current[2]));
            }
        }

        /**Add the data into the table.*/
        String[] pending_requests = temp_list.toArray(new String[0]);
        String[][] table_data = new String[pending_requests.length][3];
        for (int index = 0; index < pending_requests.length; index++){
            String[] current = pending_requests[index].split(";");
            table_data[index][0] = current[0];
            table_data[index][1] = current[1];
            table_data[index][2] = current[2];
        }

        /**Column and data added into the table for display.*/
        String[] columns = {"Date", "ID", "Requests"};
        request_table.refresh_data(columns, table_data);
        request_table.lock_data();
        request_table.column_width(new int[] {80, 70, 370});
        total_pending.text(String.format("Total Pending : %d", pending_requests.length));

        /**This section updates the total number of students active at the moment.*/
        String[] students = read.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        total_student.text(String.format("Total Active Student : %d", students.length));
    }
}
