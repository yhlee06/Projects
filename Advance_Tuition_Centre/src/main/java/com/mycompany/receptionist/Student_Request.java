package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and methods needed.*/
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;
import java.util.ArrayList;
import com.mycompany.edit.*;
import com.mycompany.gui.*;

public class Student_Request {
    /**Objects needed.*/
    static Base_Frame base;
    static Table_Frame request_table;
    static Table_Frame payment_table;
    static Dropdown_Frame search_input;
    static Read file = new Read();
    static Read_One one_line = new Read_One();

    public static void Student_Request(){
        /**Creates the base of the window.*/
        base = new Base_Frame("Student Requests", 1200, 600);

        /**Create the title label.*/
        Label_Frame request_title = new Label_Frame("Student Requests", 20, 70, 263, 39);
        request_title.font(true, 20);
        request_title.custom_design("#FFFFFF", "#0070C0");
        base.add_widget(request_title);
        Label_Frame payment_title = new Label_Frame("Student Payments", 20, 310, 263, 39);
        payment_title.font(true, 20);
        payment_title.custom_design("#FFFFFF", "#0070C0");
        base.add_widget(payment_title);

        /**Create the search bar.*/
        search_input = new Dropdown_Frame(519, 26, 20, 30, new String[] {"pending", "done", "rejected"});
        base.add_widget(search_input);
        Button_Frame search_button = new Button_Frame("Search", 128, 26, 550, 30, e -> search());
        search_button.custom_design("#C1E5F5", "#000000");
        base.add_widget(search_button);

        /**Create the table for displaying all the students.*/
        request_table = new Table_Frame(1145, 200, 20, 110);
        request_table.table.getSelectionModel().addListSelectionListener(e ->{
            if (!e.getValueIsAdjusting()){
                int row = request_table.table.getSelectedRow();
                if (row != -1){
                    String[][] table_data = request_table.get_data(new int[] {0});
                    Request_Details.Request_Details("request", table_data[row][0]);
                }
            }
        });
        base.add_widget(request_table);
        payment_table = new Table_Frame(1145, 200, 20, 350);
        payment_table.table.getSelectionModel().addListSelectionListener(e ->{
            if (!e.getValueIsAdjusting()){
                int row = payment_table.table.getSelectedRow();
                if (row != -1){
                    String[][] table_data = payment_table.get_data(new int[] {0});
                    Request_Details.Request_Details("payment", table_data[row][0]);
                }
            }
        });
        base.add_widget(payment_table);
        display_data();

        /**Set visibility of the base and link to receptionist menu.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Receptionist_Menu.menu();
                base.dispose();
            }
        });
    }

    /**Method to display the menu after visiting a different section.*/
    public static void menu(){
        display_data();
        base.setVisible(true);
    }

    /**Method to display the request and payment proofs.*/
    private static void display_data(){
        /**Data for request table.*/
        String[] request_column = {"ID", "Student", "Request", "Date", "Status"};
        String[] requests = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt");
        String[][] request_data = new String[requests.length][5];
        for (int index = 0; index < requests.length; index++){
            String[] current_request = requests[index].split(";");
            String[] student = one_line.read(current_request[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            if (student == null){
                student = new String[] {current_request[1], "Student Removed."};
            }
            request_data[index][0] = current_request[0];
            request_data[index][1] = student[1];
            request_data[index][2] = current_request[2];
            request_data[index][3] = current_request[3];
            request_data[index][4] = current_request[5];
        }
        request_table.refresh_data(request_column, request_data);
        request_table.lock_data();

        /**Data for payment proof table.*/
        String[] payment_column = {"ID", "Student", "Payment", "Date", "Status"};
        String[] payments = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt");
        String[][] payment_data = new String[payments.length][5];
        for (int index = 0; index < payments.length; index++){
            String[] current_payment = payments[index].split(";");
            String[] student = one_line.read(current_payment[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            payment_data[index][0] = current_payment[0];
            payment_data[index][1] = student[1];
            payment_data[index][2] = current_payment[2];
            payment_data[index][3] = current_payment[4];
            payment_data[index][4] = current_payment[6];
        }
        payment_table.refresh_data(payment_column, payment_data);
        payment_table.lock_data();
    }

    /**Method for searching through the data.*/
    private static void search(){
        String status = search_input.selection();

        String[] requests = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt");
        ArrayList<String> request_filter = new ArrayList<>();
        for (String request : requests){
            String[] current = request.split(";");
            if (current[5].equals(status)){
                request_filter.add(request);
            }
        }
        String[] request_filtered = request_filter.toArray(new String[0]);
        String[][] request_data = new String[request_filtered.length][5];
        for (int index = 0; index < request_filtered.length; index++){
            String[] request = request_filtered[index].split(";");
            String[] student = one_line.read(request[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            request_data[index][0] = request[0];
            request_data[index][1] = student[1];
            request_data[index][2] = request[2];
            request_data[index][3] = request[3];
            request_data[index][4] = request[5];
        }
        String[] request_column = {"ID", "Student", "Request", "Date", "Status"};
        request_table.refresh_data(request_column, request_data);
        request_table.lock_data();

        String[] payments = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt");
        ArrayList<String> payment_filter = new ArrayList<>();
        for (String payment: payments){
            String[] current = payment.split(";");
            if (current[6].equals(status)){
                payment_filter.add(payment);
            }
        }
        String[] payment_filtered = payment_filter.toArray(new String[0]);
        String[][] payment_data = new String[payment_filtered.length][5];
        for (int index = 0; index < payment_filtered.length; index++){
            String[] payment = payment_filtered[index].split(";");
            String[] student = one_line.read(payment[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            payment_data[index][0] = payment[0];
            payment_data[index][1] = student[1];
            payment_data[index][2] = payment[2];
            payment_data[index][3] = payment[4];
            payment_data[index][4] = payment[6];
        }
        String[] payment_column = {"ID", "Student", "Payment", "Date", "Status"};
        payment_table.refresh_data(payment_column, payment_data);
        payment_table.lock_data();
    }
}
