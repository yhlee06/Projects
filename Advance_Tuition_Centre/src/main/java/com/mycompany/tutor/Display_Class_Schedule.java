package com.mycompany.tutor;

import java.time.LocalDate;
import java.time.LocalTime;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import com.mycompany.edit.Read;

public class Display_Class_Schedule {
    private static Read read_file = new Read();
    
    /* Define Formatters */
    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("dd-MM-yyyy");
    private static final DateTimeFormatter TIME_FORMATTER = DateTimeFormatter.ofPattern("HHMM");

    public static String[][] class_schedule_data(Tutor tutor) {
        String[] schedule_data_lines = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_schedule.txt");
        String[] tutor_assignment_lines = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt");
        String[] class_information_lines = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt"); /* Read class information */
        
        String currentTutorID = tutor.get("id");
        
        /* Get assigned Class IDs for current tutor */
        List<String> assignedClassIDs = new ArrayList<>();
        for (String assignment_line : tutor_assignment_lines) {
            String[] parts = assignment_line.split(";");
            /* Expected format: assignmentID;tutorID;classID;year;subject */
            if (parts.length >= 3 && parts[1].equals(currentTutorID)) {
                assignedClassIDs.add(parts[2]); 
            }
        }
        
        /* Filter & Sort Data */
        List<String[]> filtered_schedule_rows = new ArrayList<>();
        
        for (String line : schedule_data_lines) {
            String[] data = line.split(";");
            
            /* Validate Data */
            /* Expected format: scheduleID;classID;date;start_time;end_time;venue */
            if (data.length >= 6) { 
                String scheduleClassId = data[1]; 
                
                /* Only add if the class is assigned to current tutor */
                if (assignedClassIDs.contains(scheduleClassId)) {
                    filtered_schedule_rows.add(data);
                }
            } else {
                System.err.println("Skipping class_schedule line: " + line + " because of an issue.");
            }
        }

        /* Sort the filtered schedule rows by Date and then by Start Time */
        Collections.sort(filtered_schedule_rows, new Comparator<String[]>() {
            @Override
            public int compare(String[] s1, String[] s2) {
                try {
                    /* Parse dates */
                    LocalDate date1 = LocalDate.parse(s1[2], DATE_FORMATTER);
                    LocalDate date2 = LocalDate.parse(s2[2], DATE_FORMATTER);

                    /* Compare Dates */
                    int dateComparison = date1.compareTo(date2);
                    if (dateComparison != 0) {
                        return dateComparison;
                    }

                    /* If dates are the same, compare start times */
                    LocalTime time1 = LocalTime.parse(s1[3], TIME_FORMATTER);
                    LocalTime time2 = LocalTime.parse(s2[3], TIME_FORMATTER);
                    return time1.compareTo(time2);

                } catch (DateTimeParseException e) {
                    System.err.println("Error parsing date/time during sorting: " + e.getMessage());
                    return 0;
                }
            }
        });
        
        /* Convert to Table */
        String[][] table_data = new String[filtered_schedule_rows.size()][7];
        for (int i = 0; i < filtered_schedule_rows.size(); i++) {
            String[] rowData = filtered_schedule_rows.get(i);
            String classId = rowData[1];
            String subjectName = findSubjectName(class_information_lines, classId);

            table_data[i][0] = rowData[0]; /* Schedule ID */
            table_data[i][1] = rowData[1]; /* Class ID */
            table_data[i][2] = subjectName; /* Subject Name */
            table_data[i][3] = rowData[2]; /* Date */
            table_data[i][4] = rowData[3]; /* Start Time */
            table_data[i][5] = rowData[4]; /* End Time */
            table_data[i][6] = rowData[5]; /* Venue */
        }

        return table_data;
    }

    /* Find Subject Name by Class ID */
    private static String findSubjectName(String[] class_information, String classId) {
        for (String class_info : class_information) {
            String[] class_data = class_info.split(";");
            if (class_data.length >= 3 && class_data[0].equals(classId)) {
                return class_data[2];
            }
        }
        return "Unknown Subject";
    }
    
    public static String[] column_title() {
        return new String[]{"Schedule ID", "Class ID", "Subject Name", "Date", "Start Time", "End Time", "Venue"};
    }
    
    public static int[] column_width() {
        return new int[]{80, 80, 200, 100, 80, 80, 100};
    }
}
