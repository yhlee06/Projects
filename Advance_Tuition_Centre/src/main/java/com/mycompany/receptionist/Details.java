package com.mycompany.receptionist; /**Package containing the abstract.*/

/**Required methods needed in subclasses.*/
public abstract class Details {
    public abstract String get(String info);
    public abstract void update(String[] info);
    public abstract boolean search(String word);
}
