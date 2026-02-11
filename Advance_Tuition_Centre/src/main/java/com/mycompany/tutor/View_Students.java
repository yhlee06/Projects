package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

import javax.swing.JFrame;

import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Table_Frame;

public class View_Students {
    static Base_Frame base;
    static Table_Frame enrollment_table;
    static Tutor current_tutor;
    
    public static void Student_Enrollment_Table(Tutor tutor) {
        current_tutor = tutor;
        base = new Base_Frame("Student List", 850, 500);
        
        enrollment_table = new Table_Frame(710, 350, 55, 80);
        Label_Frame title = new Label_Frame("Student Enrollment Information", 30, 30, 400, 35);
        title.font(true, 25);
        
        base.add_widget(title);
        base.add_widget(enrollment_table);
        
        /* Display Enrollment Data */
        String[] column_headers = Display_Student_List.enrollment_column_title();
        String[][] table_data = Display_Student_List.student_enrollment_data(current_tutor);
        int[] column_width = Display_Student_List.enrollment_column_width();
        
        /* Display Date */
        enrollment_table.refresh_data(column_headers, table_data);
        enrollment_table.lock_data();
        enrollment_table.column_width(column_width);
        
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e) {
                Tutor_Menu.menu();
                base.dispose();
            }
        });
    }
}
