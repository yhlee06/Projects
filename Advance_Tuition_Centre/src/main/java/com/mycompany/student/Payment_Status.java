package com.mycompany.student;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JFrame;
import com.mycompany.gui.*;

public class Payment_Status {
	static Base_Frame base;

	public static void Payment_Status(Payment user1) {
        base = new Base_Frame("Payment_Status", 330, 310);
        
        Label_Frame paid_label = new Label_Frame("Paid", 65, 25, 83, 26);
        paid_label.font(14); 
        Label_Frame outstanding_label = new Label_Frame("Outstanding", 190, 25, 130, 26);
        outstanding_label.font(14);
        Label_Frame payable_label = new Label_Frame("Payable", 120, 140, 79, 26);
        payable_label.font(14);
        
        Label_Frame paid_amount =  new Label_Frame(user1.get("paid"), 57, 55, 130, 26);
        paid_amount.font(true, 16);
        Label_Frame outstanding_amount =  new Label_Frame(user1.get("outstanding"), 200, 55, 130, 26);
        outstanding_amount.font(true, 16);
        Label_Frame payable_amount =  new Label_Frame(user1.get("payable"), 116, 170, 79, 26);
        payable_amount.font(true, 16);
        
        base.add_widget(paid_label);
        base.add_widget(outstanding_label);
        base.add_widget(payable_label);
        base.add_widget(paid_amount);
        base.add_widget(outstanding_amount);
        base.add_widget(payable_amount);
        
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Student_Menu.menu();
                base.dispose();
            }
        });
    }

}
