# Print student profile
def view_student_profile(student_id, student_list, course_list):
    for student in student_list:
        if student_id == student["id"]:
            print(f"\n===== User Profile =====")
            print(f"\nName: {student['name']}")
            print(f"\nID: {student['id']}")
            print(f"\nIC number: {student['ic']}")
            print(f"\nPhone number: {student['phone_no']}")
            print(f"\nIntake Code: {student['intake_code']}")

            for course in course_list:
                if course["id"] == student["course_id"]:
                    print(f"\nCourse Taken: {course['name']}")
                    break

            username = student["username"].strip('"')
            print(f"\nUsername: {username}")

            if student["emergency_name"] == "&":
                print("\n*Emergency contact name needs to be updated.")

            else:
                print(f"\nEmergency Contact Name: {student['emergency_name']}")

            if student["emergency_no"] == "!":
                print("\n*Emergency contact number needs to be updated.")

            else:
                print(f"\nEmergency Contact Number: {student['emergency_no']}")


from common_functions import edit_profile


# Student "Profile" menu
def student_profile(role, current_user_id, student_list, files, lists, course_list):
    while True:

        print("\n===== Profile =====")
        print("\na. View Profile")
        print("b. Edit Profile")
        print("c. Return to Main Menu")

        user_input = input("\nPlease choose an action to perform (a/b/c): ").strip()

        if user_input.lower() == "a":
            view_student_profile(current_user_id, student_list, course_list)

        elif user_input.lower() == "b":
            edit_profile(current_user_id, role, files, lists)

        elif user_input.lower() == "c":
            return True

        else:
            print("\nPlease insert a valid option (a/b/c).")


from common_functions import open_enrolment_file


# Check if student is alr enrolled
def is_new_enrolment(module_id, student_id):
    enrolment_list = open_enrolment_file(module_id)

    if any(entry["student_id"] == student_id for entry in enrolment_list):
        print("\nYou are already enrolled in this module.")
        return False

    return True


from common_functions import get_next_id


# Function to let student enroll in module
def enroll_module(student_id, student_list, module_list):
    student_course_id = None
    for student in student_list:
        if student["id"] == student_id:
            student_course_id = student.get("course_id")
            break

    print("\nAvailable Modules:")

    available_modules_ids = []
    for module in module_list:
        if module["course_id"] == student_course_id:
            module_name = module["name"].strip('"')
            print(f"\nModule ID: {module['id']}\nModule Name: {module_name}")
            available_modules_ids.append(module["id"])

    while True:
        module_id = input("\nEnter Module ID to enroll (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nEnrolment cancelled.")
            return

        if module_id not in available_modules_ids:
            print("\nPlease insert a valid module ID.")
            continue

        if is_new_enrolment(module_id, student_id):
            break

    enrolment_list = open_enrolment_file(module_id)

    new_id = get_next_id(enrolment_list, "ENR")

    new_enrolment = {"id": new_id, "student_id": student_id}
    enrolment_list.append(new_enrolment)

    enrolment_file = f"./dataEMS/enrolment_{module_id}.txt"
    try:
        with open(enrolment_file, "a") as file:
            file.write(f"{new_id},{student_id}\n")
            print(f"\nSuccessfully enrolled in module '{module_id}'.")
    except FileNotFoundError:
        print(f"\nError: The file '{enrolment_file}' was not found. Please check the filename or path.")


# Print module name for the module's assignments
def print_module_name(module_id, module_list):
    for module in module_list:
        if module["id"] == module_id:
            module_name = module["name"].strip('"')
            print(f"\nModule: {module_name}")
            break


from common_functions import load_module_ids


# Function to view timetable
def view_timetable(student_id, module_list, timetable_list, teacher_list):
    enrolled_module_ids = load_module_ids(student_id, module_list)

    if not enrolled_module_ids:
        print(
            "\nYou are not enrolled in any modules yet. Please choose 2 in main menu to enroll in at least one module.")
        return

    print("\n===== Timetables =====")
    for module_id in enrolled_module_ids:
        print_module_name(module_id, module_list)

        matched = False
        for timetable in timetable_list:
            if timetable["module_id"] == module_id:
                matched = True
                teacher_name = get_teacher_name(timetable["teacher_id"], teacher_list)
                print(
                    f"Day: {timetable['day']} | Time: {timetable['time']} | Venue: {timetable['venue']} | Lecturer: {teacher_name}")
                print("\n---------------------------------------------------------------")
                break

        if not matched:
            print("\nNo timetable is scheduled for this module.")


from common_functions import view_announcements


# Function to view and download course materials
def view_materials(student_id, assignment_list, module_list, announcement_list, timetable_list, teacher_list):
    while True:

        print("\n===== View Module Materials ======")
        print("\nSelect the type of material to view: ")
        print("1. Lecture Notes")
        print("2. Assignments")
        print("3. Announcements")
        print("4. Timetables")
        print("5. Return to Main Menu")

        choice = input("\nEnter your choice (1-5): ").strip()

        module_ids = load_module_ids(student_id, module_list)

        if choice == "5":
            return True


        elif choice == "1":

            print("\n===== Lecture Notes =====")

            if not module_ids:
                print(
                    "\nYou are not enrolled in any modules yet. Please choose 2 in main menu to enroll in at least one module.")

            else:

                for module in module_list:
                    if module["id"] in module_ids:

                        module_name = module["name"].strip('"')
                        print(f"\nModule: {module_name}")

                        if module["lecture_notes"] == "$":
                            print("*No link is available for this module.")

                        else:
                            lecture_notes = module["lecture_notes"].strip('"')
                            print(f"Lecture Notes Link: {lecture_notes}")


        elif choice == "2":

            print("\n===== Assignments =====")

            if not module_ids:
                print(
                    "\nYou are not enrolled in any modules yet. Please choose 2 in main menu to enroll in at least one module.")

            else:

                for module_id in module_ids:
                    assignment_exist = False
                    for module in module_list:
                        if module["id"] == module_id:
                            for assignment in assignment_list:
                                if assignment["module_id"] == module["id"]:
                                    module_name = module["name"].strip('"')
                                    print(f"\nModule Name: {module_name}")
                                    assignment_exist = True

                                    print(f"\nAssignment ID: {assignment['id']}")
                                    assignment_name = assignment["name"].strip('"')
                                    print(f"Assignment Name: {assignment_name}")

                                    if assignment["link"] == "@":
                                        print(f"Assignment Link: No link available")

                                    else:
                                        link = assignment["link"].strip('"')
                                        print(f"Assignment Link: {link}")
                                    break

                            if not assignment_exist:
                                module_name = module["name"].strip('"')
                                print(f"\nNo assignments available for module '{module_name}'.")

                            break


        elif choice == "3":
            view_announcements(announcement_list)

        elif choice == "4":
            view_timetable(student_id, module_list, timetable_list, teacher_list)

        else:
            print("\n❌ Invalid selection.")


# Check if the student has already submitted feedback for the module
def is_new_feedback(student_id, module_id, std_feedback_list):
    for feedback in std_feedback_list:
        if feedback["student_id"] == student_id and feedback["module_id"] == module_id:
            return False

    return True


from common_functions import is_valid_quoted_input
from common_functions import modify_input
from common_functions import update_file
from common_functions import append_new_line


# Function to submit feedback
def submit_feedback(student_id, module_list, std_feedback_list, files, lists):
    enrolled_module_ids = load_module_ids(student_id, module_list)

    if not enrolled_module_ids:
        print(
            "\n You are not enrolled in any modules yet. Please choose 2 in main menu to enroll in at least one module.")
        return

    print("\nYour Available Modules:")
    for module in module_list:
        if module["id"] in enrolled_module_ids:
            module_name = module["name"].strip('"')
            print(f"\nID: {module['id']}\nName: {module_name}")

    while True:
        module_id = input("\nEnter Module ID to submit feedback (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nSubmission cancelled.")
            return

        if module_id not in enrolled_module_ids:
            print("\nPlease insert a valid module ID.")

        else:
            break

    if not is_new_feedback(student_id, module_id, std_feedback_list):
        user_input = input(
            "\nYou have already submitted the feedback for this module. Do you want to update your feedback (yes/no)? ").strip().lower()

        if user_input == "yes":
            while True:
                feedback = input("\nEnter your feedback (type 'exit' to cancel): ").strip()

                if feedback.lower() == "exit":
                    print("\nSubmission cancelled.")
                    return

                if is_valid_quoted_input(feedback):
                    feedback = modify_input(feedback)

                    for std_feedback in std_feedback_list:
                        if std_feedback["student_id"] == student_id and std_feedback["module_id"] == module_id:
                            std_feedback["feedback"] = feedback
                            update_file(13, files, lists)
                            print("\nYour feedback for this module has been updated.")
                            return True

        elif user_input == "no":
            print("\nUpdate cancelled.")

        else:
            print("\nPlease insert a valid input.")

    else:

        while True:
            feedback = input("\nEnter your feedback (type 'exit' to cancel): ").strip()

            if feedback.lower() == "exit":
                print("\nSubmission cancelled.")
                return

            if is_valid_quoted_input(feedback):
                feedback = modify_input(feedback)

                new_id = get_next_id(std_feedback_list, "FB")
                new_feedback = {"id": new_id, "module_id": module_id, "student_id": student_id, "feedback": feedback}
                std_feedback_list.append(new_feedback)
                append_new_line(13, files, new_feedback)

                print("\nThank you for your feedback!")
                return True


# Function to view module details
def view_module_info(student_id, module_list, teacher_list):
    # Check if the student is enrolled in the module
    enrolled_module_ids = load_module_ids(student_id, module_list)
    print("\nAvailable Modules:")
    for module in module_list:
        if module["id"] in enrolled_module_ids:
            module_name = module["name"].strip('"')
            print(f"\nID: {module['id']}\nName: {module_name}")

    while True:
        module_id = input("\nEnter module ID (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nExiting...")
            return

        if module_id not in enrolled_module_ids:
            print("\nPlease insert a valid module ID.")

        else:
            break

    # Find the module details
    for module in module_list:
        if module["id"] == module_id:
            # Get the teacher's name
            teacher_name = get_teacher_name(module["teacher_id"], teacher_list)

            # Display module information
            print("\n===== Module Information =====")
            print("---------------------------------------------------------")
            print(f"Module ID: {module['id']}")
            module_name = module["name"].strip('"')
            print(f"Module Name: {module_name}")
            print(f"Teacher: {teacher_name}")
            lesson_plans = module["lesson_plans"].strip('"')
            print(f"Lesson Plans: {lesson_plans}")
            schedule = module["schedules"].strip('"')
            print(f"Schedule: {schedule}")
            print("---------------------------------------------------------")
            return True


# Get the teacher's name
def get_teacher_name(teacher_id, teacher_list):
    for teacher in teacher_list:
        if teacher["id"] == teacher_id:
            return teacher["name"]


# View asked questions and answers
def view_ques_ans(assistance_list, student_id):
    question_exist = False
    count = 1
    for assistance in assistance_list:
        if assistance["student_id"] == student_id:
            question_exist = True
            question = assistance["question"].strip('"')
            print(f"\n{count}.\nQuestion: {question}")

            if assistance["reply"] == "$":
                print("*This question has not been answered yet.")

            else:
                answer = assistance["reply"].strip('"')
                print(f"Answer: {answer}")

            count += 1

    if not question_exist:
        print("\nNo questions and answers to be viewed.")


# Ask questions
def ask_questions(assistance_list, student_id, files):
    while True:

        new_question = input("\nNew Question (type 'exit' to cancel): ").strip()

        if new_question.lower() == "exit":
            print("\nExiting...")
            return True

        if is_valid_quoted_input(new_question):
            question = modify_input(new_question)
            new_id = get_next_id(assistance_list, "Q")
            new_question = {"id": new_id, "student_id": student_id, "question": question, "reply": "$"}

            assistance_list.append(new_question)
            append_new_line(15, files, new_question)
            print("\nNew question added.")
            return True


# "Staff Consultation" Menu
def staff_consultation(assistance_list, student_id, files):
    while True:
        user_input = input(
            "\n===== Staff Consultation =====\n\n1. View Asked Questions and Answers\n2. Ask Questions\n3. Return to Main Menu\n\nPlease choose an action to perform (1-3): ").strip()

        if user_input == "1":
            view_ques_ans(assistance_list, student_id)

        elif user_input == "2":
            ask_questions(assistance_list, student_id, files)

        elif user_input == "3":
            return True

        else:
            print("\nPlease insert a valid number (1-3).")


from common_functions import track_grades


# Student's Main Menu
def student_main_menu(role, current_user_id, files, lists):
    student_id = current_user_id

    while True:
        print("\n===== STUDENT MANAGEMENT SYSTEM =====")
        print("\n1. User Profile")
        print("2. Browse and Enroll in Module")
        print("3. View Module Materials")
        print("4. Track Grades")
        print("5. Submit Feedback")
        print("6. View Module Info")
        print("7. Staff Consultation")
        print("8. Sign Out")

        choice = input("\nEnter your choice (1-8): ").strip()

        if choice == "1":
            student_profile(role, student_id, lists[2], files, lists, lists[7])

        elif choice == "2":
            enroll_module(student_id, lists[2], lists[5])

        elif choice == "3":
            view_materials(student_id, lists[6], lists[5], lists[14], lists[11], lists[3])

        elif choice == "4":
            track_grades(student_id, lists[5], lists[8], lists[6], lists[10], lists[9])

        elif choice == "5":
            submit_feedback(student_id, lists[5], lists[13], files, lists)

        elif choice == "6":
            view_module_info(student_id, lists[5], lists[3])

        elif choice == "7":
            staff_consultation(lists[15], student_id, files)

        elif choice == "8":
            print("\nExiting...Thank you!")
            break

        else:
            print("\nInvalid choice! Please try again.")