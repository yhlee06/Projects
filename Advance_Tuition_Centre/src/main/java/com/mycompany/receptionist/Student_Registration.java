package com.mycompany.receptionist; /**Package containing the class. */

/**Import API and classes.*/
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import com.mycompany.gui.*;
import javax.swing.JFrame;
import com.mycompany.edit.*;

public class Student_Registration {
    /**Object used in the class.*/
    static Base_Frame base;
    static Input_Frame input_name;
    static Input_Frame input_ic_passport;
    static Input_Frame input_email;
    static Input_Frame input_phone;
    static Input_Frame input_address;
    static Dropdown_Frame input_level;
    static Input_Frame input_username;
    static Input_Frame input_password;

    public static void Student_Registration(){
        /**Creates the base window.*/
        base = new Base_Frame("Student Registration", 1170, 464);

        /**Register student details label.*/
        Label_Frame register_label = new Label_Frame("Register Student", 22, 10, 208, 39);
        register_label.font(true, 16);
        base.add_widget(register_label);
        Label_Frame name_label = new Label_Frame("Name", 22, 60, 177, 26);
        name_label.font(14);
        base.add_widget(name_label);
        Label_Frame ic_passport_label = new Label_Frame("IC / Passport", 22, 100, 177, 26);
        ic_passport_label.font(14);
        base.add_widget(ic_passport_label);
        Label_Frame email_label = new Label_Frame("Email", 22, 140, 177, 26);
        email_label.font(14);
        base.add_widget(email_label);
        Label_Frame phone_label = new Label_Frame("Phone Number", 562, 60, 177, 26);
        phone_label.font(14);
        base.add_widget(phone_label);
        Label_Frame address_label = new Label_Frame("Address", 562, 100, 177, 26);
        address_label.font(14);
        base.add_widget(address_label);
        Label_Frame level_label = new Label_Frame("Form", 562, 140, 177, 26);
        level_label.font(14);
        base.add_widget(level_label);

        /**Inputs for student details. */
        input_name = new Input_Frame(341, 26, 199, 60);
        base.add_widget(input_name);
        input_ic_passport = new Input_Frame(341, 26, 199, 100);
        base.add_widget(input_ic_passport);
        input_email = new Input_Frame(341, 26, 199, 140);
        base.add_widget(input_email);
        input_phone = new Input_Frame(341, 26, 739, 60);
        base.add_widget(input_phone);
        input_address = new Input_Frame(341, 26, 739, 100);
        base.add_widget(input_address);
        String[] level_options = {"1", "2", "3", "4", "5"};
        input_level = new Dropdown_Frame(341, 26, 739, 140, level_options);
        base.add_widget(input_level);

        /**Student account labels.*/
        Label_Frame account_label = new Label_Frame("Account", 22, 200, 128, 39);
        account_label.font(true, 16);
        base.add_widget(account_label);
        Label_Frame username_label = new Label_Frame("Username", 22, 240, 177, 26);
        username_label.font(14);
        base.add_widget(username_label);
        Label_Frame password_label = new Label_Frame("Password", 22, 280, 177, 26);
        password_label.font(14);
        base.add_widget(password_label);
        
        /**Input for account details.*/
        input_username = new Input_Frame(341, 26, 199, 240);
        base.add_widget(input_username);
        input_password = new Input_Frame(341, 26, 199, 280);
        base.add_widget(input_password);
        
        /**Button for registering the student.*/
        Button_Frame register_button = new Button_Frame("REGISTER", 272, 34, 449, 350, e -> register());
        register_button.custom_design("#D9F2D0", "#000000");
        base.add_widget(register_button);

        /**Set the visibility of the window and redirect to student management menu when close.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Student_Management.menu();
                base.dispose();
            }
        });
    }

    /**Private method for registering the student.*/
    private static void register(){
        /**Objects for reading, adding and updating text files.*/
        Read file = new Read();
        Add add_student = new Add();
        Update update_total = new Update();

        /**Check if the username is taken.*/
        String[] accounts = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt");
        for (String account : accounts){
            String[] data = account.split(";");
            if (data[1].equals(input_username.get_input())){
                Message_Frame.message_frame("Error", "Username is already taken.");
                return;
            }
        }

        /**Checks if the password is long enough.*/
        if (input_password.get_input().length() < 8){
            Message_Frame.message_frame("Error", "Password needs to be minimum 8 characters.");
            return;
        }

        /**Update total number of student in the total text file.*/
        String[] total_list = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt");
        String[] current;
        int student_total = 0 ;
        for (int i = 0; i < total_list.length; i++){
            current = total_list[i].split(";");
            if (current[0].equals("student")){
                student_total = Integer.parseInt(current[1]) + 1;
            }
        }
        String student_id = String.format("S%03d", student_total);

        /**Checks for empty inputs.*/
        String[] input_data = {input_name.get_input(), input_ic_passport.get_input(), input_email.get_input(), input_phone.get_input(), input_address.get_input(), input_level.selection(), input_username.get_input(), input_password.get_input()};
        for (String check : input_data){
            if (check.isBlank()){
                Message_Frame.message_frame("Warning", "Please fill up all requirement.");
                return;
            }
        }

        /**Format for adding into the text file.*/
        String new_student = String.format("%s;%s;%s;%s;%s;%s;%s", student_id, input_name.get_input(), input_ic_passport.get_input(), input_email.get_input(), input_phone.get_input(), input_address.get_input(), input_level.selection());
        String new_account = String.format("%s;%s;%s;student", student_id, input_username.get_input(), input_password.get_input());
        String new_payment = String.format("%s;0;0;0", student_id);

        /**Save new student, create account and set up payment status.*/
        add_student.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt", new_student);
        add_student.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/user.txt", new_account);
        add_student.add_to_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_status.txt", new_payment);
        update_total.update_file("Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt", "student", 1, String.valueOf(student_total));

        /**Close the window and redirect to the student management.*/
        base.dispose();
        Student_Management.menu();
    }
}
