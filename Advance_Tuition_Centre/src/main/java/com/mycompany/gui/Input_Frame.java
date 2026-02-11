package com.mycompany.gui;

import javax.swing.*;

public class Input_Frame extends JPanel{
    public JTextField input_field;

    public Input_Frame(int width, int height, int x, int y){
        setLayout(null);
        setBounds(x, y, width, height);
        input_field = new JTextField();
        input_field.setBounds(0, 0, width, height);
        add(input_field);
    }

    public String get_input(){
        String input = input_field.getText();
        return input;
    }

    public void set_text(String text){
        input_field.setText(text);
    }
}