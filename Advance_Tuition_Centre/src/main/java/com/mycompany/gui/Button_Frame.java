package com.mycompany.gui;

import java.awt.Color;
import java.awt.Image;
import java.awt.event.*;
import javax.swing.*;

public class Button_Frame extends JPanel{
    JButton button;

    public Button_Frame(String title, int width, int height, int x, int y, ActionListener action){
        setLayout(null);
        setBounds(x, y, width, height);

        button = new JButton(title);
        button.setBounds(0, 0, width, height);
        button.setFocusPainted(false);
        button.addActionListener(action);
        add(button);
    }

    public Button_Frame(int width, int height, int x, int y, String image_path, int scale_x, int scale_y, ActionListener action){
        setLayout(null);
        setBounds(x, y, width, height);

        ImageIcon icon = new ImageIcon(image_path);
        Image scaled = icon.getImage().getScaledInstance(scale_x, scale_y, Image.SCALE_SMOOTH);
        button = new JButton(new ImageIcon(scaled));
        button.setBounds(0, 0, width, height);
        button.setFocusPainted(false);
        button.setBackground(Color.WHITE);
        button.addActionListener(action);
        add(button);
    }

    public void status(boolean condition){
        button.setEnabled(condition);
    }

    public void custom_design(String background_colour, String font_colour){
        button.setBackground(Color.decode(background_colour));
        button.setForeground(Color.decode(font_colour));
    }
}