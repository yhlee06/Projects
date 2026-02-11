package com.mycompany.tutor;

import java.util.ArrayList;
import java.util.List;

import com.mycompany.edit.Read;

public class Display_Student_List {
    private static Read read_file = new Read();
    
    public static String[][] student_enrollment_data(Tutor tutor) {
            /* Read Data */
        String[] student_enrollments = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student_enrolment.txt");
        String[] student_data = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/student.txt");
        String[] tutor_assignments = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt");
        String[] class_information = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
        
        /* Get classes that are assigned to the current tutor */
        List<String> assigned_classes = new ArrayList<>();
        for (String assignment : tutor_assignments) {
            String[] assign_info = assignment.split(";");
            /* Ensure enough parts and check if tutor ID matches */
            if (assign_info.length >= 3 && assign_info[1].equals(tutor.get("id"))) {
                assigned_classes.add(assign_info[2]); /* Add class ID */
            }
        }
        
        /* Build enrollment table data */
        List<String[]> table_rows = new ArrayList<>();
        
        if (student_enrollments != null && student_enrollments.length > 0) {
            for (String enrollment : student_enrollments) {
                String[] enroll_info = enrollment.split(";");
                
                /* Validate enrollment data length */
                /* Expected format: enrollmentID;studentID;classID;dateEnrolled;dateCompleted */
                if (enroll_info.length >= 5) {
                    String studentId = enroll_info[1];
                    String classId = enroll_info[2];
                    String dateEnrolled = enroll_info[3];
                    String dateCompleted = enroll_info[4];
                    
                    /* Only add if the class is assigned to the current tutor */
                    if (assigned_classes.contains(classId)) {
                        String studentName = findStudentName(student_data, studentId);
                        String subjectName = findSubjectName(class_information, classId);
                        
                        /* Handle "null" dateCompleted status */
                        if ("null".equals(dateCompleted)) {
                            dateCompleted = "Not Completed";
                        }
                        
                        /* Create row for the table */
                        String[] row = new String[6];
                        row[0] = studentId;
                        row[1] = studentName;
                        row[2] = classId;
                        row[3] = subjectName;
                        row[4] = dateEnrolled;
                        row[5] = dateCompleted;
                        
                        table_rows.add(row);
                    }
                }
            }
        }
            /* Convert list to 2D array for table display */
        return table_rows.toArray(new String[0][6]);
    }
    
        /* Helper method to find student name by student ID */
    private static String findStudentName(String[] student_data, String studentId) {
        for (String student : student_data) {
            String[] student_info = student.split(";");
            if (student_info.length >= 2 && student_info[0].equals(studentId)) {
                return student_info[1]; 
            }
        }
        return "Unknown Student";
    }
    
    /* Helper method to find subject name by class ID */
    private static String findSubjectName(String[] class_information, String classId) {
        for (String class_info : class_information) {
            String[] class_data = class_info.split(";");
            if (class_data.length >= 3 && class_data[0].equals(classId)) {
                return class_data[2]; /* Return subject name */
            }
        }
        return "Unknown Subject";
    }
    
    public static String[] enrollment_column_title() {
        return new String[]{"Student ID", "Student Name", "Class ID", "Subject Name", "Date Enrolled", "Date Completed"};
    }
    
    public static int[] enrollment_column_width() {
        return new int[]{80, 150, 80, 180, 100, 120};
    }
}
