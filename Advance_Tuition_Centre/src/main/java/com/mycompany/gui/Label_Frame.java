package com.mycompany.gui;

import javax.swing.*;
import java.awt.*;

public class Label_Frame extends JPanel{
    JLabel label;
    public Label_Frame(String text, int x, int y, int width, int height){
        label = new JLabel(text);
        setLayout(null);
        setBounds(x, y, width, height);
        label.setBounds(0, 0, width, height);
        setBackground(Color.WHITE);
        add(label);
    }

    public void font(int size){
        label.setFont(new Font("Arial", Font.PLAIN, size));
    }

    public void font(boolean bold, int size){
        if (bold == true){
            label.setFont(new Font("Arial", Font.BOLD, size));
        }
        else{
            label.setFont(new Font("Arial", Font.PLAIN, size));
        }
    }

    public void text(String message){
        label.setText(message);
    }

    public void custom_design(String background_colour, String font_colour){
        label.setBackground(Color.decode(background_colour));
        label.setForeground(Color.decode(font_colour));
    }
}
