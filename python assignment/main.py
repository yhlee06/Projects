from signup_login import open_file
from signup_login import sign_up
from signup_login import log_in


# Save file paths as a dictionary
files = {
    1: "./dataEMS/admin.txt",
    2: "./dataEMS/student.txt",
    3: "./dataEMS/teacher.txt",
    4: "./dataEMS/staff.txt",
    5: "./dataEMS/modules.txt",
    6: "./dataEMS/assignment.txt",
    7: "./dataEMS/course.txt",
    8: "./dataEMS/assign_grades.txt",
    9: "./dataEMS/module_grades.txt",
    10: "./dataEMS/exam_grades.txt",
    11: "./dataEMS/timetable.txt",
    12: "./dataEMS/resources.txt",
    13: "./dataEMS/std_feedback.txt",
    14: "./dataEMS/announcement.txt",
    15: "./dataEMS/assistance.txt",
    16: "./dataEMS/financial.txt",
    17: "./dataEMS/event.txt"
}

admin_list = []
student_list = []
teacher_list = []
staff_list = []
module_list = []
assignment_list = []
course_list = []
assign_grade_list = []
module_grade_list = []
exam_grade_list = []
timetable_list = []
resource_list = []
std_feedback_list = []
announcement_list = []
assistance_list = []
financial_list = []
event_list = []

# Save data lists as a dictionary
lists = {
    1: admin_list,
    2: student_list,
    3: teacher_list,
    4: staff_list,
    5: module_list,
    6: assignment_list,
    7: course_list,
    8: assign_grade_list,
    9: module_grade_list,
    10: exam_grade_list,
    11: timetable_list,
    12: resource_list,
    13: std_feedback_list,
    14: announcement_list,
    15: assistance_list,
    16: financial_list,
    17: event_list
}

files_opened = True
# Read and save data into every list in the lists dictionary
for list_no in lists:
    if not open_file(list_no, files, lists):
        files_opened = False
        break

if files_opened:
    while True:

        print("\nWelcome to the Education Management System!")
        print("\n1. Sign Up")
        print("2. Log In")
        print("3. Quit program")

        action = input("\nPlease choose an action to perform (1-3): ").strip()

        if action == "3":
            print("\nExiting program...")
            break

        if action not in ["1", "2", "3"]:
            print("\nPlease insert a valid number (1-3).")
            continue

        role_chosen = False
        while True:
            try:
                print("\n1. Admin")
                print("2. Student")
                print("3. Teacher")
                print("4. Staff")

                role = input("\nPlease choose your role (1-4) (type 'exit' to cancel): ").strip()

                if role.lower() == "exit":
                    break

                role = int(role)
                if 0 < role < 5:
                    role_chosen = True
                    break

                else:
                    print("\nPlease insert a valid number (1-4).")

            except ValueError:
                print("\nPlease insert a valid number (1-4).")

        if role_chosen:
            if action == "1":

                if sign_up(role, lists, files):
                    log_in(role, lists, files)

            elif action == "2":

                log_in(role, lists, files)