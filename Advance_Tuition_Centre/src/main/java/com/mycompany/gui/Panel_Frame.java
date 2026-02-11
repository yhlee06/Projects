package com.mycompany.gui;

import javax.swing.*;

public class Panel_Frame extends JPanel {
	public Panel_Frame(String borderTitle, int x, int y, int width, int height) {
		setLayout(null);
		setBounds(x, y, width, height);
		setBorder(BorderFactory.createTitledBorder(borderTitle));
	}
	
	public void add_weight(JComponent widget) {
		add(widget);
	}

}
