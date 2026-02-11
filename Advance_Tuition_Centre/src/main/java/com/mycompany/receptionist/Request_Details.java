package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and classes needed.*/
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFileChooser;
import java.io.File;
import javax.swing.JFrame;
import com.mycompany.edit.*;

public class Request_Details {
    /**Objects and variables used.*/
    static Dropdown_Frame status;
    static Read_One file = new Read_One();
    static String payment_proof_id;

    public static void Request_Details(String detail_type, String id){
        /**Creates the base frame.*/
        Base_Frame base = new Base_Frame("Details", 584, 400);
        /**Variables and arrays used.*/
        String[] student;
        String[] options;
        Label_Frame student_label;
        Label_Frame time_label;

        switch (detail_type){ /**Checks what kind of request it is to display information.*/
            case "request": /**Request Type*/
            /**Get the name of the student sending the request.*/
            String[] request = file.read(id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt");
            student = file.read(request[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");

            if (student == null){
                student = new String[] {request[1], "Student No Longer Exists"};
            }

            /**Display the student, student ID and time of request sent.*/
            student_label = new Label_Frame(String.format("%s (%s)", student[1], student[0]), 20, 20, 500, 24);
            student_label.font(true, 16);
            student_label.custom_design("#FFFFFF", "#0070C0");
            base.add_widget(student_label);
            time_label = new Label_Frame(String.format("%s, %s", request[3], request[4]), 20, 40, 100, 24);
            time_label.font(12);
            base.add_widget(time_label);

            /**Status section as a dropdown.*/
            options = new String[] {"pending", "done", "rejected"};
            status = new Dropdown_Frame(100, 25, 20, 70, options);
            switch (request[5]){
                case "pending" : status.set_selection(0); break;
                case "done" : status.set_selection(1); break;
                case "rejected" : status.set_selection(2); break;
            }
            base.add_widget(status);

            /**Request label and text frame containing the request message.*/
            Label_Frame request_label = new Label_Frame("Request", 20, 100, 100, 24);
            request_label.font(14);
            base.add_widget(request_label);
            TextArea_Frame request_message = new TextArea_Frame(520, 200, 20, 130);
            request_message.set_text(request[2]);
            request_message.text_area.setEditable(false);
            base.add_widget(request_message);
            break;

            case "payment": /**Payment proof from student.*/
            /**Get student name.*/
            String[] payment = file.read(id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt");
            student = file.read(payment[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            payment_proof_id = id;
            
            /**Display student, student ID and time payment proof was sent.*/
            student_label = new Label_Frame(String.format("%s (%s)", student[1], student[0]), 20, 20, 200, 24);
            student_label.font(true, 16);
            student_label.custom_design("#FFFFFF", "#0070C0");
            base.add_widget(student_label);
            time_label = new Label_Frame(String.format("%s, %s", payment[4], payment[5]), 20, 40, 100, 24);
            time_label.font(12);
            base.add_widget(time_label);

            /**Button to generate receipt.*/
            Button_Frame receipt_button = new Button_Frame("GENERATE RECEIPT", 200, 25, 140, 70, e -> generate());
            receipt_button.custom_design("#FFFFCC", "#000000");
            base.add_widget(receipt_button);

            /**Dropdown options for the status.*/
            options = new String[] {"pending", "done"};
            status = new Dropdown_Frame(100, 25, 20, 70, options);
            switch(payment[6]){
                case "pending" : status.set_selection(0); break;
                case "done" : status.set_selection(1); break;
            }
            base.add_widget(status);
            
            /**Payment label and text frame containing the payment message.*/
            Label_Frame payment_label = new Label_Frame("Payment", 20, 100, 100, 24);
            payment_label.font(14);
            base.add_widget(payment_label);

            TextArea_Frame payment_message = new TextArea_Frame(520, 100, 20, 130);
            payment_message.set_text(payment[2]);
            payment_message.text_area.setEditable(false);
            base.add_widget(payment_message);

            /**Text frame containing the link to the payment proof.*/
            TextArea_Frame payment_proof = new TextArea_Frame(520, 80, 20, 250);
            payment_proof.set_text(payment[3]);
            base.add_widget(payment_proof);
            break;
        }

        /**Action listener added to allow auto update status when changed options.*/
        status.dropdown.addActionListener(e ->{
            Update update = new Update();
            String update_status = status.selection();
            switch (detail_type){
                case "request" : update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt", id, 5, update_status); break;
                case "payment" : update.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt", id, 6, update_status); break;
            }
        });

        /**Sets the visibility of the base and links to student request.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Student_Request.menu();
                base.dispose();
            }
        });
    }

    /**Method for generating receipt.*/
    private static void generate(){
        Add add = new Add(); /**Object for adding to text file.*/
        /**Input amount and check if input is valid.*/
        String amount = Message_Frame.input_frame("Amount", "Enter amount paid");
        if (amount == null){
            return;
        }
        try{
            double check = Double.parseDouble(amount);
        } catch (Exception e){
            Message_Frame.message_frame("Error", "Please enter valid amount.");
            return;
        }

        /**Chooses a file.*/
        JFileChooser chooser = new JFileChooser();
        chooser.setDialogTitle("Select directory to save receipt");
        chooser.setFileSelectionMode(JFileChooser.DIRECTORIES_ONLY);

        int result = chooser.showSaveDialog(null);
        if (result == JFileChooser.APPROVE_OPTION) { 
            /**Gets the file directory.*/
            File directory = chooser.getSelectedFile();
            File file_path = new File(directory, String.format("Receipt-%s.txt", payment_proof_id));
            
            /**Checks if the file exists.*/
            if (file_path.exists()){
                Message_Frame.message_frame("Warning", "File already exists.");
                return;
            }

            /**Gets information about the student and all the payments.*/
            String[] payment_proof = file.read(payment_proof_id, "Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt");
            String[] student = file.read(payment_proof[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
            String[] payment = file.read(payment_proof[1], "Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt");

            /**Details to be showed on the receipt.*/
            add.add_to_file(file_path.toString(), "Receipt============================================");
            add.add_to_file(file_path.toString(), String.format("Name\t\t: %s", student[1]));
            add.add_to_file(file_path.toString(), String.format("ID\t\t: %s", student[0]));
            add.add_to_file(file_path.toString(), String.format("Amount Paid\t: %s", amount));
            add.add_to_file(file_path.toString(), String.format("Total Paid\t: %s", payment[1]));
            add.add_to_file(file_path.toString(), String.format("Outstanding\t: %s", payment[2]));
            add.add_to_file(file_path.toString(), String.format("Total To Pay\t: %s", payment[3]));

            /**Message that the receipt is generated.*/
            Message_Frame.message_frame("Success", "Receipt Generated.");
        }
    }
}
