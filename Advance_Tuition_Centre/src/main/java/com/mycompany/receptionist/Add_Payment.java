package com.mycompany.receptionist; /**Package containing the class.*/

/**Import API and classes.*/
import com.mycompany.edit.*;
import com.mycompany.gui.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;

public class Add_Payment {
    /**Create variables used in the class.*/
    static Base_Frame base;
    static Input_Frame amount_input;
    static Profile student;

    public static void Add_Payment(Profile selected){
        student = selected;
        Read file = new Read();
        /**Creates the base window.*/
        base = new Base_Frame("Payment", 584, 220);

        /**Creates the labels.*/
        Label_Frame id_label = new Label_Frame("Student ID", 22, 40, 177, 26);
        id_label.font(14);
        base.add_widget(id_label);
        Label_Frame student_id_label = new Label_Frame(student.get_profile("id"), 199, 40, 100, 26);
        student_id_label.font(14);
        base.add_widget(student_id_label);
        Label_Frame amount_label = new Label_Frame("Amount Paid", 22, 80, 177, 26);
        amount_label.font(14);
        base.add_widget(amount_label);

        /**Creates the input frames.*/
        amount_input = new Input_Frame(341, 26, 199, 80);
        base.add_widget(amount_input);

        /**Creates the button.*/
        Button_Frame add_payment_button = new Button_Frame("PAID", 272, 34, 156, 120, e -> add_payment()); 
        base.add_widget(add_payment_button);

        /**Code for closing the window and linking to the previous section to be visible.*/
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                base.dispose();
            }
        });
    }

    /**Private method to add paid amount and update paid, outstanding and total.*/
    private static void add_payment(){
        /**Checks if the input is a valid value.*/
        double amount_paid;
        try{
            amount_paid = Double.parseDouble(amount_input.get_input());
        } catch (Exception e){
            Message_Frame.message_frame("Error", "Invalid amount.");
            return;
        }

        /**Updates and close the window and redirect back to the student details menu.*/
        student.pay(amount_paid);
        Student_Details.display_payment();
        base.dispose();
    }
}
