package com.mycompany.gui;

import java.awt.Color;
import javax.swing.*;

public class Base_Frame extends JFrame{
    JPanel panel = new JPanel();
    JLayeredPane layer = new JLayeredPane();

    public Base_Frame(String title, int width, int height){
        setTitle(title);
        setSize(width, height);
        setResizable(false);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLayout(null);

        layer.setBounds(0, 0, width, height);
        add(layer);

        panel.setBounds(0, 0, width, height);
        panel.setBackground(Color.WHITE);
        layer.add(panel, JLayeredPane.DEFAULT_LAYER);
    }

    public void add_widget(JPanel widget){
        layer.add(widget, JLayeredPane.PALETTE_LAYER);
    }
}