package com.mycompany.student;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

import javax.swing.JFrame;

import com.mycompany.edit.Read;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Label_Frame;

public class Request_Menu {
	static Base_Frame base;
	static Read file = new Read();

	public static void Request_Menu(Student user) {
		
		base = new Base_Frame("Request Menu ", 400, 400);

		Label_Frame request_menu_label = new Label_Frame("Request Menu", 130, 25, 150, 26);
		request_menu_label.font(22);
		
		Button_Frame view_request = new Button_Frame("VIEW REQUEST", 200, 40, 100, 100, e -> select("view request", user));
        Button_Frame change_subject = new Button_Frame("CHANGE SUBJECT", 200, 40, 100, 160, e -> select("change subject", user));
        Button_Frame drop_subject = new Button_Frame("DROP SUBJECT", 200, 40, 100, 220, e -> select("drop subject", user));
        Button_Frame others = new Button_Frame("OTHERS", 200, 40, 100, 280, e -> select("others", user));

		base.add_widget(request_menu_label);
		base.add_widget(view_request);
		base.add_widget(change_subject);
		base.add_widget(drop_subject);
		base.add_widget(others);
		base.setVisible(true);
		base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Student_Menu.menu();
                base.dispose();
            }
        });
		
	}

    public static void select(String selection, Student user){
        base.setVisible(false);
        switch (selection){
        	case "view request": View_Request.View_Request(user); break;
            case "change subject": Change_Subject.Change_Subject(user); break;
            case "drop subject": Drop_Subject.Drop_Subject(user); break;
            case "others": Others.Others(user); break;
        }
    }

    public static void menu(){
        base.setVisible(true);
        
    }

}
