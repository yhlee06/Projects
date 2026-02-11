package com.mycompany.gui;

import javax.swing.*;

public class Password_Frame extends JPanel {
    JPasswordField password_frame;

    public Password_Frame(int width, int height, int x, int y){
        setLayout(null);
        setBounds(x, y, width, height);
        password_frame = new JPasswordField();
        password_frame.setBounds(0, 0, width, height);
        add(password_frame);
    }

    public String get_input(){
        return String.valueOf(password_frame.getPassword());
    }

    public void set_text(String text){
        password_frame.setText(text);
    }
}
