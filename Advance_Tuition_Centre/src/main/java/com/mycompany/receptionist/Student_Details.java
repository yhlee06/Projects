package com.mycompany.receptionist; /**Package that contains the class.*/

/**Import API and methods needed.*/
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;
import com.mycompany.edit.Delete;

public class Student_Details {
    /**Objects used.*/
    static Base_Frame base;
    static Profile student;
    static Table_Frame student_profile;
    static Table_Frame student_enrolment;
    static Table_Frame student_payment;

    public static void Student_Details(String student_id){
        /**Creates the base frame and create a student object.*/
        base = new Base_Frame("Student Details", 1080, 520);
        student = new Profile(student_id);

        /**Create the title label.*/
        Label_Frame title_label = new Label_Frame("Student Details", 20, 20, 200, 30);
        title_label.font(true, 20);
        title_label.custom_design("#FFFFFF", "#0070C0");
        base.add_widget(title_label);
        Button_Frame delete_button = new Button_Frame("Delete Student", 130, 24, 910, 20, e -> select("delete"));
        delete_button.custom_design("#F6C6AD", "#000000");
        base.add_widget(delete_button);

        /**Profile Section*/
        /**Creates the profile label and the save profile button.*/
        Label_Frame profile_label = new Label_Frame(String.format("Profile (%s)", student.get_profile("id")), 20, 60, 120, 24);
        profile_label.font(true, 16);
        base.add_widget(profile_label);
        Button_Frame save_profile_button = new Button_Frame("Save Update", 130, 24, 340, 60, e -> select("update_profile"));
        save_profile_button.custom_design("#FFFFCC", "#000000");
        base.add_widget(save_profile_button);

        /**Creates the table displaying the student profile.*/
        student_profile = new Table_Frame(450, 383, 20, 84);
        base.add_widget(student_profile);
        display_profile();

        /**Enrolment Section*/
        /**Creates the enrolment label and navigation buttons.*/
        Label_Frame enrolment_label = new Label_Frame("Enrolment", 500, 60, 120, 24);
        enrolment_label.font(true, 16);
        base.add_widget(enrolment_label);
        Button_Frame enrol_button = new Button_Frame("Enrol", 130, 24, 775, 60, e -> select("enrol"));
        enrol_button.custom_design("#D9F2D0", "#000000");
        base.add_widget(enrol_button);
        Button_Frame save_enrol_button = new Button_Frame("Save", 130, 24, 910, 60, e -> select("update_enrolment"));
        save_enrol_button.custom_design("#FFFFCC", "#000000");
        base.add_widget(save_enrol_button);

        /**Creates the table displaying the enrolment.*/
        student_enrolment = new Table_Frame(540, 160, 500, 84);
        base.add_widget(student_enrolment);
        display_enrolment();

        /**Payment Section*/
        /**Creates the payment label and navigation buttons.*/
        Label_Frame payment_label = new Label_Frame("Payment", 500, 260, 100, 24);
        payment_label.font(true, 16);
        base.add_widget(payment_label);
        Button_Frame pay_button = new Button_Frame("Pay", 130, 24, 640, 260, e -> select("pay"));
        pay_button.custom_design("#D9F2D0", "#000000");
        base.add_widget(pay_button);
        Button_Frame save_payment_button = new Button_Frame("Save", 130, 24, 775, 260, e -> select("update_payment"));
        save_payment_button.custom_design("#FFFFCC", "#000000");
        base.add_widget(save_payment_button);
        Button_Frame refresh_button = new Button_Frame("Refresh", 130, 24, 910, 260, e -> select("refresh_payment"));
        refresh_button.custom_design("#DCEAF7", "#000000");
        base.add_widget(refresh_button);

        student_payment = new Table_Frame(540, 180, 500, 284);
        base.add_widget(student_payment);
        display_payment();


        /**Makes the window visible and also links this to the main menu when closed.*/
        base.setVisible(true);
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Student_Management.menu();
                base.dispose();
            }
        });
    }

    /**Method for displaying the student's profile.*/
    private static void display_profile(){
        String[][] profile_data = new String[8][2];
        
        String[] details = {"Name", "IC/Passport", "Email", "Contact Number", "Address", "Level", "Username", "Password"};
        for (int index = 0; index < profile_data.length; index++){
            profile_data[index][0] = details[index];
        }

        profile_data[0][1] = student.get_profile("name");
        profile_data[1][1] = student.get_profile("ic");
        profile_data[2][1] = student.get_profile("email");
        profile_data[3][1] = student.get_profile("phone");
        profile_data[4][1] = student.get_profile("address");
        profile_data[5][1] = student.get_profile("level");
        profile_data[6][1] = student.get_account("username");
        profile_data[7][1] = student.get_account("password");

        student_profile.refresh_data(new String[] {"Detail", "Info"}, profile_data);
        student_profile.table.setRowHeight(45);
        student_profile.column_width(new int[] {100, 350});
    }

    /**Method to display student's enrolment.*/
    public static void display_enrolment(){
        String[] class_names = student.get_enrolment("name");
        String[] start = student.get_enrolment("start");
        String[] end = student.get_enrolment("end");

        if (class_names[0].isBlank()){
            student_enrolment.refresh_data(new String[] {"Class", "Start", "End"}, null);
            student_enrolment.column_width(new int[] {340, 100, 100});
            return;
        }

        String[][] enrolment_data = new String[class_names.length][3];
        for (int index = 0; index < class_names.length; index++){
            enrolment_data[index][0] = class_names[index];
            enrolment_data[index][1] = start[index];
            enrolment_data[index][2] = end[index];
        }

        student_enrolment.refresh_data(new String[] {"Class", "Start", "End"}, enrolment_data);
        student_enrolment.column_width(new int[] {340, 100, 100});
    }

    /**Method to display student's payment status.*/
    public static void display_payment(){
        String[][] payment_data = new String[3][2];
        payment_data[0][0] = "Paid";
        payment_data[1][0] = "Outstanding";
        payment_data[2][0] = "Total";
        payment_data[0][1] = student.get_payment("paid");
        payment_data[1][1] = student.get_payment("outstanding");
        payment_data[2][1] = student.get_payment("total");

        student_payment.refresh_data(new String[] {"Details", "Amount"}, payment_data);
        student_payment.column_width(new int[] {440, 100});
        student_payment.table.setRowHeight(52);
    }

    /**Method for navigation and to execute required actions.*/
    private static void select(String selection){
        switch(selection){
            case "update_profile":
                String[][] student_data = student_profile.get_data(new int[] {1});
                String[] profile = {student_data[0][0], student_data[1][0], student_data[2][0], student_data[3][0], student_data[4][0], student_data[5][0]};
                String[] account = {student_data[6][0], student_data[7][0]};
                
                for (String check : profile){
                    if (check.isBlank()){
                        Message_Frame.message_frame("Error", "Please ensure all information is filled up.");
                        return;
                    }
                }

                for (String check : account){
                    if (check.trim().isEmpty()){
                        Message_Frame.message_frame("Error", "Please ensure all information is filled up.");
                        return;
                    }
                }

                student.update_profile(profile);
                student.update_account(account);
                break;
            case "enrol":
                Enrol_Student.Enrol_Student(student.get_profile("id"));
                break;
            case "update_enrolment":
                String[][] enrolment_data = student_enrolment.get_data(new int[] {0, 1, 2});
                String[] enrolment_id = student.get_enrolment("id");
                String[] enrolment = new String[enrolment_data.length];

                for (int index = 0; index < enrolment_data.length; index++){
                    if (enrolment_data[index][1].isBlank() || enrolment_data[index][2].isBlank()){
                        Message_Frame.message_frame("Error", "Empty cell found");
                        display_enrolment();
                        return;
                    }
                    String update_enrolment = String.format("%s;%s;%s", enrolment_id[index], enrolment_data[index][1], enrolment_data[index][2]);
                    enrolment[index] = update_enrolment;
                }
                student.update_enrolment(enrolment);
                display_enrolment();
                break;
            case "pay":
                Add_Payment.Add_Payment(student);
                break;
            case "update_payment":
                String[][] payment_data = student_payment.get_data(new int[] {1});
                String[] update_payment = {payment_data[0][0], payment_data[1][0], payment_data[2][0]};
                student.update_payment(update_payment);
                display_payment();
                break;
            case "refresh_payment":
                student.refresh_payment();
                display_payment();
                break;
            case "delete":
                double outstanding = Double.parseDouble(student.get_payment("outstanding"));
                if (outstanding > 0){
                    Message_Frame.message_frame("Warning", "This student hasn't completed their payment.");
                    return;
                } else {
                    boolean confirm = Message_Frame.confirm_frame("Warning", "Do you really want to delete this student?");
                    if (confirm == true){
                        Delete delete = new Delete();
                        delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt", student.get_profile("id"));
                        delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt", student.get_profile("id"));
                        String[] enrolments = student.get_enrolment("id");
                        for (String enrol_id : enrolments){
                            delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt", enrol_id);
                        }
                        Student_Management.menu();
                        base.dispose();
                    }
                }
                break;
        }
    }
}