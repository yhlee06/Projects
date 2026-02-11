# Print teacher profile
def view_teacher_profile(current_user_id, teacher_list, module_list, teacher_module_id):
    for teacher in teacher_list:
        if current_user_id == teacher["id"]:
            print(f"\n===== User Profile =====")
            print(f"\nName: {teacher['name']}")
            print(f"\nID: {teacher['id']}")
            print(f"\nIC number: {teacher['ic']}")
            print(f"\nPhone number: {teacher['phone_no']}")
            username = teacher["username"].strip('"')
            print(f"\nUsername: {username}")

            if teacher_module_id != "*":
                for module in module_list:
                    if teacher["module_id"] == module["id"]:
                        module_name = module["name"].strip('"')
                        print(f"\nModule Teaching: {module_name}")
                        break

            if teacher["emergency_name"] == "&":
                print("\n*Emergency contact name needs to be updated.")

            else:
                print(f"\nEmergency Contact Name: {teacher['emergency_name']}")

            if teacher["emergency_no"] == "!":
                print("\n*Emergency contact number needs to be updated.")

            else:
                print(f"\nEmergency Contact Number: {teacher['emergency_no']}")


from common_functions import edit_profile


# "Profile" menu
def teacher_profile(current_user_id, teacher_list, role, module_list, teacher_module_id, files, lists):
    while True:

        print("\n===== Profile =====")
        print("\na. View Profile")
        print("b. Edit Profile")
        print("c. Return to Main Menu")

        user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

        if user_input == "a":
            view_teacher_profile(current_user_id, teacher_list, module_list, teacher_module_id)

        elif user_input == "b":
            edit_profile(current_user_id, role, files, lists)

        elif user_input == "c":
            return

        else:
            print("\nPlease insert a valid option (a/b/c).")


# Match and save the id of the module taught by current teacher
def save_teacher_module_id(current_user_id, teacher_list):
    for teacher in teacher_list:
        if current_user_id == teacher["id"]:
            return teacher["module_id"]


# Check if the teacher is teaching a module
def exist_teacher_module(teacher_module_id):
    if teacher_module_id == "*":
        print("\nYou have no access to this function.")
        return False

    else:
        return True


# Print module name and lesson plans
def teacher_view_lesson_plans(module_list, teacher_module_id):
    for module in module_list:
        if teacher_module_id == module["id"]:
            module_name = module["name"].strip('"')
            print(f"\nModule Teaching: {module_name}")

            if module["lesson_plans"] == "@":
                print("\n*Lesson plans need to be updated.")

            else:
                lesson_plans = module["lesson_plans"].strip('"')
                print(f"\nLesson Plans: {lesson_plans}")

            return True


from common_functions import update_lesson_plans


# "Lesson Plans" menu
def teacher_lesson_plans(module_list, teacher_module_id, files, lists):
    while True:

        print("\n===== Lesson Plans =====")
        print("\n1. View Lesson Plans")
        print("2. Update Lesson Plans")
        print("3. Return to 'Course Management' Menu")

        user_input = input("\nPlease choose an action to perform (1-3): ").strip()

        if user_input == "1":
            teacher_view_lesson_plans(module_list, teacher_module_id)

        elif user_input == "2":
            update_lesson_plans(teacher_module_id, module_list, files, lists)

        elif user_input == "3":
            return True

        else:
            print("\nPlease insert a valid number (1-3).")


# Check if the assignment name existed
def is_valid_assignment_name(teacher_module_id, assignment_list, new_assignment_name):
    for assignment in assignment_list:
        if teacher_module_id == assignment["module_id"]:
            if new_assignment_name.lower() == assignment["name"].strip('"').lower():
                print("\nAssignment already exists.")
                return False

    return True


from common_functions import is_valid_quoted_input
from common_functions import modify_input
from common_functions import get_next_id
from common_functions import append_new_line


# Create new assignment
def teacher_create_assignment(teacher_module_id, files, module_list, current_user_id, assignment_list, assignment_ids):
    for module in module_list:
        if teacher_module_id == module["id"]:
            while True:
                module_name = module["name"].strip('"')
                user_input = input(
                    f"\nCreate a new assignment for module '{module_name}'? (type 'yes' to proceed or 'exit' to cancel) ").strip().lower()

                if user_input == "exit":
                    print("\nAssignment creation cancelled.")
                    return

                elif user_input == "yes":
                    break

                else:
                    print("\nPlease insert a valid input.")

            while True:

                new_assignment_name = input("\nAssignment Name (type 'exit' to cancel): ").strip()

                if new_assignment_name.lower() == "exit":
                    print("\nAssignment creation cancelled.")
                    return

                if is_valid_quoted_input(new_assignment_name) and is_valid_assignment_name(teacher_module_id,
                                                                                           assignment_list,
                                                                                           new_assignment_name):
                    new_assignment_name = modify_input(new_assignment_name)
                    new_id = get_next_id(assignment_list, "AS")

                    new_assignment = {"id": new_id, "name": new_assignment_name, "module_id": teacher_module_id,
                                      "teacher_id": current_user_id, "link": "@"}
                    assignment_list.append(new_assignment)
                    append_new_line(6, files, new_assignment)

                    assignment_ids.append(new_id)

                    print("\nNew assignment added.")
                    return True


from common_functions import update_file


# Update assignment name
def teacher_update_assign_name(assignment_id, assignment_list, teacher_module_id, files, lists):
    for assignment in assignment_list:
        if assignment_id == assignment["id"]:
            while True:
                new_assignment_name = input("\nAssignment Name (type 'exit' to cancel): ").strip()

                if new_assignment_name.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if is_valid_quoted_input(new_assignment_name):

                    if new_assignment_name.lower() == assignment["name"].strip('"').lower():
                        print("\nYou have inserted an old assignment name.")
                        continue

                    if is_valid_assignment_name(teacher_module_id, assignment_list, new_assignment_name):
                        new_assignment_name = modify_input(new_assignment_name)
                        assignment["name"] = new_assignment_name
                        update_file(6, files, lists)
                        print("\nAssignment name updated.")
                        return True


# Update assignment link
def teacher_update_assign_link(assignment_id, assignment_list, files, lists):
    for assignment in assignment_list:
        if assignment_id == assignment["id"]:

            if assignment["link"] != "@":
                while True:
                    user_input = input(
                        "\nThe link of this assignment has been updated. Are you sure you want to change? (yes/no): ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            new_assignment_link = input("\nAssignment link (type 'exit' to cancel): ").strip()

            if new_assignment_link.lower() == "exit":
                print("\nUpdate cancelled.")
                return

            new_assignment_link = modify_input(new_assignment_link)

            assignment["link"] = new_assignment_link
            update_file(6, files, lists)
            print("\nAssignment link updated.")
            return True


# "Update Assignment" Menu
def teacher_update_assignment(assignment_list, teacher_module_id, files, lists, assignment_ids):
    print("\n===== Update Assignment =====")

    if not assignment_ids:
        print("\nNo assignments available for this module. This action cannot be performed.")
        return

    print("\nAvailable Assignments:")
    for assignment in assignment_list:
        if assignment["id"] in assignment_ids:
            assignment_name = assignment["name"].strip('"')
            print(f"ID: {assignment['id']}, Name: {assignment_name}")

    while True:
        assignment_id = input("\nPlease insert an assignment ID to proceed (type 'exit' to cancel): ").strip().upper()

        if assignment_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return True

        if assignment_id in assignment_ids:
            while True:

                print("\n===== Update Assignment =====")
                print("\n1. Update Assignment Name")
                print("2. Update Assignment Link")
                print("3. Return to 'Assignments' Menu")

                update_no = input("\nPlease choose an action to perform (1-3): ").strip()

                if update_no.lower() == "1":
                    teacher_update_assign_name(assignment_id, assignment_list, teacher_module_id, files, lists)

                elif update_no.lower() == "2":
                    teacher_update_assign_link(assignment_id, assignment_list, files, lists)

                elif update_no.lower() == "3":
                    return True

                else:
                    print("\nPlease insert a valid option (1-3).")

        else:
            print("\nPlease insert a valid assignment ID.")


# View assignments
def teacher_view_assignments(assignment_ids, assignment_list):
    print("\nAssignments:")

    if not assignment_ids:
        print("\n*No assignments are created for this module.")
        return

    number = 1
    for assignment in assignment_list:
        if assignment["id"] in assignment_ids:
            assignment_name = assignment["name"].strip('"')
            print(f"\n{number}.\nID: {assignment['id']}\nName: {assignment_name}")

            if assignment["link"] == "@":
                print("\n*Assignment link needs to be updated.")

            else:
                assignment_link = assignment["link"].strip('"')
                print(f"\nLink: {assignment_link}")

            number += 1

    return True


# Remove assignment
def teacher_remove_assignment(assignment_list, assignment_ids, files, lists):
    if not assignment_ids:
        print("\nNo assignments available for this module. This action cannot be performed.")
        return

    print("\nAvailable Assignments:")
    for assignment in assignment_list:
        if assignment["id"] in assignment_ids:
            assignment_name = assignment["name"].strip('"')
            print(f"ID: {assignment['id']}, Name: {assignment_name}")

    while True:
        assignment_id = input("\nPlease insert an assignment ID to proceed (type 'exit' to cancel): ").strip().upper()

        if assignment_id.lower() == "exit":
            print("\nAssignment removal cancelled.")
            return True

        if assignment_id in assignment_ids:
            for assignment in assignment_list:
                if assignment["id"] == assignment_id:
                    assignment_list.remove(assignment)
                    update_file(6, files, lists)
                    print("\nSuccessfully removed.")
                    return True

        else:
            print("\nPlease insert a valid assignment ID.")


from common_functions import save_assignment_id


# "Assignments" Menu
def teacher_assignments(teacher_module_id, module_list, lists, current_user_id, assignment_list, files):
    assignment_ids = save_assignment_id(assignment_list, teacher_module_id)

    while True:

        print("\n===== Assignments =====")
        print("\n1. View Assignments")
        print("2. Create New Assignment")
        print("3. Update Assignment")
        print("4. Remove Assignment")
        print("5. Return to 'Course Management' Menu")

        user_input = input("\nPlease choose an action to perform (1-4): ").strip()

        if user_input == "1":
            teacher_view_assignments(assignment_ids, assignment_list)

        elif user_input == "2":
            teacher_create_assignment(teacher_module_id, files, module_list, current_user_id, assignment_list,
                                      assignment_ids)

        elif user_input == "3":
            teacher_update_assignment(assignment_list, teacher_module_id, files, lists, assignment_ids)

        elif user_input == "4":
            teacher_remove_assignment(assignment_list, assignment_ids, files, lists)

        elif user_input == "5":
            return True

        else:
            print("\nPlease insert a valid number (1-4).")


# View schedules
def teacher_view_schedules(teacher_module_id, module_list):
    for module in module_list:
        if teacher_module_id == module["id"]:
            if module["schedules"] == "&":
                print("\n*Schedule needs to be updated.")

            else:
                schedule = module["schedules"].strip('"')
                print(f"\nSchedule: {schedule}")

            return True


from common_functions import update_schedules


# "Schedules" Menu
def teacher_schedules(teacher_module_id, module_list, files, lists):
    while True:

        print("\n===== Schedules =====")
        print("\na. View Schedules")
        print("b. Update Schedules")
        print("c. Return to 'Course Management' Menu")

        user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

        if user_input == "a":
            teacher_view_schedules(teacher_module_id, module_list)

        elif user_input == "b":
            update_schedules(teacher_module_id, module_list, files, lists)

        elif user_input == "c":
            return True

        else:
            print("\nPlease insert a valid input (a/b/c).")


# View lecture notes
def teacher_view_notes(module_list, teacher_module_id):
    for module in module_list:
        if teacher_module_id == module["id"]:
            if module["lecture_notes"] == "$":
                print("\n*Lecture notes need to be updated.")

            else:
                lecture_notes = module["lecture_notes"].strip('"')
                print(f"\nLecture Notes Link: {lecture_notes}")

            return True


from common_functions import update_notes


# "Lecture Notes" Menu
def teacher_lecture_notes(module_list, teacher_module_id, files, lists):
    while True:

        print("\n===== Lecture Notes =====")
        print("\na. View Lecture Notes")
        print("b. Update Lecture Notes")
        print("c. Return to 'Course Management' Menu")

        user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

        if user_input == "a":
            teacher_view_notes(module_list, teacher_module_id)

        elif user_input == "b":
            update_notes(module_list, teacher_module_id, files, lists)

        elif user_input == "c":
            return True

        else:
            print("\nPlease insert a valid input (a/b/c).")


# View timetable
def teacher_view_timetable(module_list, teacher_module_id, timetable_list, resource_list):
    for module in module_list:
        if module["id"] == teacher_module_id:
            module_name = module["name"].strip('"')

    timetable_found = False
    for timetable in timetable_list:
        if timetable["module_id"] == teacher_module_id:
            timetable_found = True
            print("\n====== Timetable ======")
            print(f"\nModule: {module_name}")
            print(f"\nDay: {timetable['day']} | Time: {timetable['time']} | Venue: {timetable['venue']}")
            resource_id = timetable["resource_id"]
            break

    if not timetable_found:
        print("\nThe timetable for this module is not scheduled yet.")
        return

    if resource_id == "$":
        print("\n*No resource is allocated for this class.")
        return True

    for resource in resource_list:
        if resource["id"] == resource_id:
            print(f"\nResource Allocated: {resource['name']} X 1")
            return True


# View student feedback
def teacher_view_feedback(teacher_module_id, std_feedback_list):
    feedback_exist = False
    print("\n===== Student Feedback =====")
    count = 1
    for feedback in std_feedback_list:
        if feedback["module_id"] == teacher_module_id:
            feedback_exist = True
            std_feedback = feedback['feedback'].strip('"')
            print(f"\n{count}. {std_feedback}")
            count += 1

    if not feedback_exist:
        print("\n*No student feedback for this module.")


# "Module Management" menu
def teacher_module_management(module_list, teacher_module_id, std_feedback_list, lists, current_user_id,
                              assignment_list, files, timetable_list, resource_list):
    if exist_teacher_module(teacher_module_id):
        while True:

            print("\n===== Module Management =====")
            print("\n1. Lesson Plans")
            print("2. Assignments")
            print("3. Schedules")
            print("4. Lecture Notes")
            print("5. View Timetable")
            print("6. View Student Feedback")
            print("7. Return to Main Menu")

            user_input = input("\nPlease choose an action to perform (1-7): ").strip()

            if user_input.lower() == "1":
                teacher_lesson_plans(module_list, teacher_module_id, files, lists)

            elif user_input.lower() == "2":
                teacher_assignments(teacher_module_id, module_list, lists, current_user_id, assignment_list, files)

            elif user_input.lower() == "3":
                teacher_schedules(teacher_module_id, module_list, files, lists)

            elif user_input.lower() == "4":
                teacher_lecture_notes(module_list, teacher_module_id, files, lists)

            elif user_input.lower() == "5":
                teacher_view_timetable(module_list, teacher_module_id, timetable_list, resource_list)

            elif user_input.lower() == "6":
                teacher_view_feedback(teacher_module_id, std_feedback_list)

            elif user_input.lower() == "7":
                return True

            else:
                print("\nPlease insert a valid option (1-7).")

    else:
        return True


from common_functions import open_enrolment_file
from common_functions import view_enrolled_students
from common_functions import enrol_student
from common_functions import remove_student


# "Student Enrolment" menu
def teacher_student_enrolment(teacher_module_id, student_list, module_list, course_list):
    if exist_teacher_module(teacher_module_id):
        enrolment_list = open_enrolment_file(teacher_module_id)

        while True:

            print("\n===== Student Enrolment =====")
            print("\na. View Enrolled Students")
            print("b. Enrol Student")
            print("c. Remove Student")
            print("d. Return to Teacher's Main Menu")

            user_input = input("\nPlease choose an action to perform (a/b/c/d): ").strip().lower()

            if user_input == "a":
                view_enrolled_students(enrolment_list, student_list)

            elif user_input == "b":
                enrol_student(enrolment_list, student_list, module_list, teacher_module_id, course_list)

            elif user_input == "c":
                remove_student(enrolment_list, student_list, teacher_module_id)

            elif user_input == "d":
                return

            else:
                print("\nPlease insert a valid option (a/b/c/d).")


# Check if the student's assignment has been graded before
def is_new_assign_grade(assign_grade_list, assignment_id, student_id):
    for assign_grade in assign_grade_list:
        if student_id == assign_grade["student_id"] and assignment_id == assign_grade["assignment_id"]:
            print(
                "\nThe assignment of this student is graded before. "
                "Please return to the 'Assignment' Menu to update grades or feedback.")
            return False

    return True


# Calculate grade
def calculate_grade(score):
    if 80 <= score <= 100:
        return "A+"

    elif 75 <= score <= 79:
        return "A"

    elif 70 <= score <= 74:
        return "B+"

    elif 65 <= score <= 69:
        return "B"

    elif 60 <= score <= 64:
        return "C+"

    elif 55 <= score <= 59:
        return "C"

    elif 50 <= score <= 54:
        return "D+"

    elif 40 <= score <= 49:
        return "D"

    else:
        return "F"


# Calculate final grade of a module
def calculate_module_final_grade(avrg_assign_score, exam_score):
    final_score = (avrg_assign_score + exam_score) / 2

    final_grade = calculate_grade(final_score)

    return final_grade


# Calculate the average assignment score of a module
def calculate_avrg_assign_score(assign_grade_list, teacher_module_id, student_id):
    number_of_assign = 0
    total_score = 0

    for assign_grade in assign_grade_list:
        if assign_grade["module_id"] == teacher_module_id and assign_grade["student_id"] == student_id:
            number_of_assign += 1
            total_score += int(assign_grade["score"])

    if number_of_assign == 0:
        avrg_assign_score = 0

    else:
        avrg_assign_score = total_score / number_of_assign

    return avrg_assign_score


# Get exam score of a module
def get_exam_score(exam_grade_list, teacher_module_id, student_id):
    for exam_grade in exam_grade_list:
        if exam_grade["module_id"] == teacher_module_id and exam_grade["student_id"] == student_id:
            exam_score = int(exam_grade["score"])
            return exam_score

    exam_score = 0
    return exam_score


# Update final grade in the module grade file
def update_module_grade(module_grade_list, teacher_module_id, student_id, files, lists, assign_grade_list,
                        current_user_id, exam_grade_list):
    avrg_assign_score = calculate_avrg_assign_score(assign_grade_list, teacher_module_id, student_id)
    exam_score = get_exam_score(exam_grade_list, teacher_module_id, student_id)
    final_grade = calculate_module_final_grade(avrg_assign_score, exam_score)

    for module_grade in module_grade_list:
        if module_grade["module_id"] == teacher_module_id and module_grade["student_id"] == student_id:
            module_grade["final_grade"] = final_grade
            update_file(9, files, lists)
            return True

    new_grade_id = get_next_id(module_grade_list, "G")
    new_module_grade = {"id": new_grade_id, "student_id": student_id, "module_id": teacher_module_id,
                        "teacher_id": current_user_id, "final_grade": final_grade}
    module_grade_list.append(new_module_grade)
    append_new_line(9, files, new_module_grade)


from common_functions import is_valid_id
from common_functions import is_student_enrolled


# Grade assignments and provide feedback
def new_grade_feedback_assignment(student_list, assign_grade_list, enrolment_list, teacher_module_id,
                                  current_assignment_id, current_user_id, module_grade_list, files, lists,
                                  exam_grade_list):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nGrading cancelled.")
            return

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list,
                                                                         student_id) and is_new_assign_grade(
                assign_grade_list, current_assignment_id, student_id):
            break

    while True:
        try:
            score = input("\nScore (type 'exit' to cancel): ").strip()

            if score.lower() == "exit":
                print("\nGrading cancelled.")
                return

            score = int(score)

            if 0 <= score <= 100:
                break

            else:
                print("\nPlease insert a valid score (0-100).")

        except ValueError:
            print("\nPlease insert a valid score (0-100).")

    grade = calculate_grade(score)

    while True:
        feedback = input("\nFeedback (type 'exit' to cancel): ").strip()

        if feedback.lower() == "exit":
            print("\nGrading cancelled.")
            return

        if is_valid_quoted_input(feedback):
            feedback = modify_input(feedback)
            break

    new_id = get_next_id(assign_grade_list, "AG")
    new_assign_grade = {"id": new_id, "assignment_id": current_assignment_id, "module_id": teacher_module_id,
                        "teacher_id": current_user_id, "student_id": student_id, "grade": grade, "score": score,
                        "feedback": feedback}
    assign_grade_list.append(new_assign_grade)
    append_new_line(8, files, new_assign_grade)

    update_module_grade(module_grade_list, teacher_module_id, student_id, files, lists, assign_grade_list,
                        current_user_id, exam_grade_list)
    print("\nSuccessfully graded.")
    return True


# Check if the record of the assignment grade and feedback exists
def is_exist_assign_grade(assign_grade_list, student_id, current_assignment_id):
    for assign_grade in assign_grade_list:
        if assign_grade["student_id"] == student_id and assign_grade["assignment_id"] == current_assignment_id:
            return True

    print(
        "\nThis student's assignment has not been graded before. Please grade the assignment first to use the 'Update' function.")
    return False


# Update assignment grades
def update_assign_grade(student_list, enrolment_list, assign_grade_list, current_assignment_id, files, lists,
                        teacher_module_id, module_grade_list, exam_grade_list, current_user_id):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list,
                                                                         student_id) and is_exist_assign_grade(
                assign_grade_list, student_id, current_assignment_id):
            break

    while True:

        try:
            new_score = input("\nNew Score (type 'exit' to cancel): ").strip()

            if new_score.lower() == "exit":
                print("\nUpdate cancelled.")
                return

            new_score = int(new_score)

            if 0 <= new_score <= 100:
                break

            else:
                print("\nPlease insert a valid score (0-100).")

        except ValueError:
            print("\nPlease insert a valid score (0-100).")

    new_assign_grade = calculate_grade(new_score)

    for assign_grade in assign_grade_list:
        if assign_grade["assignment_id"] == current_assignment_id and assign_grade["student_id"] == student_id:
            assign_grade["score"] = new_score
            assign_grade["grade"] = new_assign_grade
            update_file(8, files, lists)
            break

    update_module_grade(module_grade_list, teacher_module_id, student_id, files, lists, assign_grade_list,
                        current_user_id, exam_grade_list)
    print("\nSuccessfully updated.")
    return True


# Update assignment feedback
def update_assign_feedback(student_list, enrolment_list, assign_grade_list, current_assignment_id, files, lists):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list,
                                                                         student_id) and is_exist_assign_grade(
                assign_grade_list, student_id, current_assignment_id):
            break

    while True:
        new_feedback = input("\nFeedback (type 'exit' to cancel): ").strip()

        if new_feedback.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_quoted_input(new_feedback):
            new_feedback = modify_input(new_feedback)
            break

    for assign_grade in assign_grade_list:
        if assign_grade["assignment_id"] == current_assignment_id and assign_grade["student_id"] == student_id:
            assign_grade["feedback"] = new_feedback
            update_file(8, files, lists)
            print("\nSuccessfully updated.")
            return True


# Grade assignments, provide feedback, update grades and update feedback
def grade_feedback_assignments(assignment_list, teacher_module_id, student_list, enrolment_list, assign_grade_list,
                               current_user_id, module_grade_list, files, lists, exam_grade_list):
    assignment_ids = save_assignment_id(assignment_list, teacher_module_id)

    while True:
        print("\n===== Assignments =====")
        print("\nAvailable Assignments: ")

        for assignment in assignment_list:
            if assignment["id"] in assignment_ids:
                assignment_name = assignment["name"].strip('"')
                print(f"ID: {assignment['id']}, Name: {assignment_name}")

        print("\n1. Grade Assignments and Provide Feedback")
        print("2. Update Assignment Grades")
        print("3. Update Assignment Feedback")
        print("4. Return to 'Grading and Assessment' Menu")

        user_input = input("\nPlease choose an action to perform (1-4): ").strip()

        if user_input == "4":
            return

        if user_input in ["1", "2", "3"]:
            while True:
                current_assignment_id = input(
                    "\nPlease insert an assignment ID to proceed (type 'exit' to cancel): ").strip().upper()

                if current_assignment_id.lower() == "exit":
                    print("\nExiting...")
                    break

                if current_assignment_id not in assignment_ids:
                    print("\nPlease insert a valid assignment ID.")
                    continue

                if user_input == "1":
                    new_grade_feedback_assignment(student_list, assign_grade_list, enrolment_list, teacher_module_id,
                                                  current_assignment_id, current_user_id, module_grade_list, files,
                                                  lists, exam_grade_list)
                    break

                elif user_input == "2":
                    update_assign_grade(student_list, enrolment_list, assign_grade_list, current_assignment_id, files,
                                        lists, teacher_module_id, module_grade_list, exam_grade_list, current_user_id)
                    break

                elif user_input == "3":
                    update_assign_feedback(student_list, enrolment_list, assign_grade_list, current_assignment_id,
                                           files, lists)
                    break
        else:
            print("\nPlease insert a valid number (1-4).")


# Check if the student's exam has been graded before
def is_new_exam_grade(exam_grade_list, teacher_module_id, student_id):
    for exam_grade in exam_grade_list:
        if exam_grade["module_id"] == teacher_module_id and exam_grade["student_id"] == student_id:
            print(
                "\nThis student has been graded before. Please return to the 'Assignment' Menu to update exam grades. ")
            return False

    return True


# Grade Exams
def new_grade_exams(student_list, enrolment_list, exam_grade_list, teacher_module_id, current_user_id,
                    module_grade_list, files, lists, assign_grade_list):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nGrading cancelled.")
            return True

        if not is_valid_id(student_id, student_list):
            continue

        if not is_student_enrolled(enrolment_list, student_id):
            continue

        if not is_new_exam_grade(exam_grade_list, teacher_module_id, student_id):
            continue  # Message is already printed inside the function

        break  # Student is valid, exit loop

    while True:
        try:
            score = input("\nNew Score (type 'exit' to cancel): ").strip()

            if score.lower() == "exit":
                print("\nGrading cancelled.")
                return

            score = int(score)

            if 0 <= score <= 100:
                break

            else:
                print("\nPlease insert a valid score (0-100).")

        except ValueError:
            print("\nPlease insert a valid score (0-100).")

    grade = calculate_grade(score)
    new_exam_grade_id = get_next_id(exam_grade_list, "EG")
    exam_grade = {"id": new_exam_grade_id, "module_id": teacher_module_id, "teacher_id": current_user_id,
                  "student_id": student_id, "grade": grade, "score": score}
    exam_grade_list.append(exam_grade)
    append_new_line(10, files, exam_grade)

    update_module_grade(module_grade_list, teacher_module_id, student_id, files, lists, assign_grade_list,
                        current_user_id, exam_grade_list)
    print("\nSuccessfully graded.")
    return True


# Check if the exam grade record exists
def is_exist_exam_grade(exam_grade_list, teacher_module_id, student_id):
    for exam_grade in exam_grade_list:
        if exam_grade["module_id"] == teacher_module_id and exam_grade["student_id"] == student_id:
            return True

    print("\nThis student has not been graded before. Please grade the exam first to use the 'Update' function.")
    return False


# Update exam grades
def update_exam_grade(student_list, enrolment_list, exam_grade_list, teacher_module_id, files, lists, module_grade_list,
                      assign_grade_list, current_user_id):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list,
                                                                         student_id) and is_exist_exam_grade(
            exam_grade_list, teacher_module_id, student_id):
            break

    while True:
        try:
            new_score = input("\nNew Score (type 'exit' to cancel): ").strip()

            if new_score.lower() == "exit":
                print("\nUpdate cancelled.")
                return

            new_score = int(new_score)

            if 0 <= new_score <= 100:
                break

            else:
                print("\nPlease insert a valid score (0-100).")

        except ValueError:
            print("\nPlease insert a valid score (0-100).")

    new_grade = calculate_grade(new_score)

    for exam_grade in exam_grade_list:
        if exam_grade["module_id"] == teacher_module_id and exam_grade["student_id"] == student_id:
            exam_grade["score"] = new_score
            exam_grade["grade"] = new_grade
            update_file(10, files, lists)
            break

    update_module_grade(module_grade_list, teacher_module_id, student_id, files, lists, assign_grade_list,
                        current_user_id, exam_grade_list)
    print("\nSuccessfully updated.")
    return True


# Grade exams and update grades
def grade_exams(student_list, enrolment_list, exam_grade_list, teacher_module_id, current_user_id, module_grade_list,
                files, lists, assign_grade_list):
    while True:

        print("\n===== Exams =====")
        print("\n1. Grade Exams")
        print("2. Update Exam Grades")
        print("3. Return to 'Grading and Assessment' Menu")

        user_input = input("\nPlease choose an action to perform: ").strip()

        if user_input == "1":
            new_grade_exams(student_list, enrolment_list, exam_grade_list, teacher_module_id, current_user_id,
                            module_grade_list, files, lists, assign_grade_list)

        elif user_input == "2":
            update_exam_grade(student_list, enrolment_list, exam_grade_list, teacher_module_id, files, lists,
                              module_grade_list, assign_grade_list, current_user_id)

        elif user_input == "3":
            return True

        else:
            print("\nPlease insert a valid number (1-3).")


# "Grading and Assessment" menu
def teacher_grading_assessment(teacher_module_id, assignment_list, student_list, assign_grade_list, current_user_id,
                               module_grade_list, files, lists, exam_grade_list):
    if exist_teacher_module(teacher_module_id):
        enrolment_list = open_enrolment_file(teacher_module_id)

        while True:

            print("\n===== Grading and Assessment =====")
            print("\na. Assignments")
            print("b. Exams")
            print("c. Return to Teacher's Main Menu")

            user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

            if user_input == "a":
                grade_feedback_assignments(assignment_list, teacher_module_id, student_list, enrolment_list,
                                           assign_grade_list, current_user_id, module_grade_list, files, lists,
                                           exam_grade_list)

            elif user_input == "b":
                grade_exams(student_list, enrolment_list, exam_grade_list, teacher_module_id, current_user_id,
                            module_grade_list, files, lists, assign_grade_list)

            elif user_input == "c":
                return

            else:
                print("\nPlease insert a valid option (a/b/c).")

    else:
        return


# Check if the attendance is a new record
def is_new_attendance(attendance_list, student_id, date):
    for attendance in attendance_list:
        if attendance["student_id"] == student_id and attendance["date"] == date:
            print("\nThis attendance is already recorded. Please avoid creating duplicated records.")
            return False

    return True


# Append new record into attendance file of a module
def append_attendance(teacher_module_id, new_attendance_list):
    file_path = f"./dataEMS/attendance_{teacher_module_id}.txt"

    try:
        with open(file_path, "a") as file:
            for new_attendance in new_attendance_list:
                file.write(",".join(map(str, new_attendance.values())) + "\n")

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")


from common_functions import is_valid_date


# Record student attendance
def teacher_rec_attendance(attendance_list, student_list, enrolment_list, teacher_module_id):
    new_attendance_list = []
    new_record = False
    exit = False
    while True:

        student_id = input("\nEnter student ID (type 'exit' to quit): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nExiting...")
            break

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list, student_id):

            while True:
                date = input("\nEnter date (YYYY-MM-DD) (type 'exit' to cancel): ").strip()

                if date.lower() == "exit":
                    print("\nExiting...")
                    exit = True
                    break

                if is_valid_date(date):
                    break

            if exit:
                break

            if is_new_attendance(attendance_list, student_id, date):

                while True:
                    status = input(
                        "\nStatus (Present/Absent/Late/Excused) (type 'exit' to cancel): ").strip().capitalize()

                    if status.lower() == "exit":
                        exit = True
                        print("\nExiting...")
                        break

                    if status not in ["Present", "Absent", "Late", "Excused"]:
                        print("\nPlease insert a valid status (Present/Absent/Late/Excused).")
                        continue

                    new_id = get_next_id(attendance_list, "ATT")
                    new_attendance = {"id": new_id, "student_id": student_id, "date": date, "status": status}
                    new_attendance_list.append(new_attendance)
                    attendance_list.append(new_attendance)
                    print("\nSuccessfully recorded.")
                    new_record = True
                    break

                if exit:
                    break

    if new_record:
        append_attendance(teacher_module_id, new_attendance_list)
        return True


# Check if the attendance record exists
def exist_attendance(attendance_list, student_id):
    for attendance in attendance_list:
        if attendance["student_id"] == student_id:
            return True

    return False


# View attendance
def teacher_view_attendance(student_list, enrolment_list, attendance_list):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nExiting...")
            return

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list, student_id):

            if exist_attendance(attendance_list, student_id):

                print(f"\nAttendance Records for Student {student_id}:")

                for attendance in attendance_list:
                    if attendance["student_id"] == student_id:
                        print(
                            f"\nAttendance ID: {attendance['id']}\nDate: {attendance['date']}\nStatus: {attendance['status']}")

            else:
                print("\nNo attendance record is found.")

            return True


from common_functions import open_attendance_file


# "Attendance Tracking" Menu
def teacher_track_attendance(teacher_module_id, student_list):
    if exist_teacher_module(teacher_module_id):
        enrolment_list = open_enrolment_file(teacher_module_id)
        attendance_list = open_attendance_file(teacher_module_id)

        while True:

            print("\n===== Attendance Tracking =====")
            print("\na. Record Student Attendance")
            print("b. View Student Attendance Records")
            print("c. Return to Teacher's Main Menu")

            user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

            if user_input == "a":
                teacher_rec_attendance(attendance_list, student_list, enrolment_list, teacher_module_id)

            elif user_input == "b":
                teacher_view_attendance(student_list, enrolment_list, attendance_list)

            elif user_input == "c":
                return

            else:
                print("\nPlease insert a valid option (a/b/c).")


from common_functions import get_assign_grade
from common_functions import get_exam_grade
from common_functions import get_final_grade


# Generate a report on a student's performance
def report_std_performance(student_list, module_list, enrolment_list, module_grade_list, exam_grade_list,
                           assign_grade_list, assignment_list, teacher_module_id):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nReport generation cancelled.")
            return True

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list, student_id):

            print("\n===== Student Performance Report =====")

            for module in module_list:
                if module["id"] == teacher_module_id:
                    module_name = module["name"].strip('"')
                    print(f"\nModule: {module_name}")
                    break

            print(f"\nStudent ID: {student_id}")

            print("\n--- Assignments ---")
            get_assign_grade(assign_grade_list, student_id, assignment_list, teacher_module_id)

            print("\n--- Exam ---")
            get_exam_grade(exam_grade_list, teacher_module_id, student_id)
            get_final_grade(module_grade_list, student_id, teacher_module_id)
            return True


# Get the total number of classes that should be attended by the student
def get_total_classes(attendance_list, student_id):
    count = 0
    for attendance in attendance_list:
        if attendance["student_id"] == student_id:
            count += 1

    return count


# Get the total number of classes attended by the student
def get_participation(attendance_list, student_id, status):
    count = 0
    for attendance in attendance_list:
        if attendance["student_id"] == student_id and attendance["status"] == status:
            count += 1

    return count


# Generate a report on a student's participation
def report_std_participation(student_list, module_list, teacher_module_id, enrolment_list, attendance_list):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nReport generation cancelled.")
            return True

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list, student_id):

            total_classes = get_total_classes(attendance_list, student_id)
            total_present = get_participation(attendance_list, student_id, "Present")
            total_late = get_participation(attendance_list, student_id, "Late")
            total_absent = get_participation(attendance_list, student_id, "Absent")
            total_excused = get_participation(attendance_list, student_id, "Excused")

            print("\n===== Student Participation Report =====")

            for module in module_list:
                if module["id"] == teacher_module_id:
                    module_name = module["name"].strip('"')
                    print(f"\nModule: {module_name}")
                    break

            print(f"\nStudent ID: {student_id}")
            print(f"\nTotal Classes Attended: {total_present + total_late} / {total_classes}")
            print("\n----- Attendance Rate -----")
            print(f"\nPresent: {round(float(total_present / total_classes * 100), 2) if total_classes > 0 else 0}%")
            print(f"Late: {round(float(total_late / total_classes * 100), 2) if total_classes > 0 else 0}%")
            print(f"Absent: {round(float(total_absent / total_classes * 100), 2) if total_classes > 0 else 0}%")
            print(f"Excused: {round(float(total_excused / total_classes * 100), 2) if total_classes > 0 else 0}%")
            return True


# "Report Generation" Menu
def teacher_generate_report(teacher_module_id, module_list, student_list, module_grade_list, exam_grade_list,
                            assign_grade_list, assignment_list):
    if exist_teacher_module(teacher_module_id):
        enrolment_list = open_enrolment_file(teacher_module_id)
        attendance_list = open_attendance_file(teacher_module_id)

        while True:

            print("\n===== Report Generation =====")
            print("\na. Student Performance")
            print("b. Student Participation")
            print("c. Return to Teacher's Main Menu")

            user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

            if user_input == "a":
                report_std_performance(student_list, module_list, enrolment_list, module_grade_list, exam_grade_list,
                                       assign_grade_list, assignment_list, teacher_module_id)

            elif user_input == "b":
                report_std_participation(student_list, module_list, teacher_module_id, enrolment_list, attendance_list)

            elif user_input == "c":
                return True

            else:
                print("\nPlease insert a valid option (a/b/c).")


# Teacher's Main Menu
def teacher_main_menu(role, current_user_id, files, lists):
    teacher_module_id = save_teacher_module_id(current_user_id, lists[3])

    while True:

        print("\n===== Teacher's Main Menu =====")
        print("\n1. User Profile")
        print("2. Module Management")
        print("3. Student Enrolment")
        print("4. Grading and Assessment")
        print("5. Attendance Tracking")
        print("6. Report Generation")
        print("7. Sign Out")

        user_input = input("\nPlease choose an action to perform (1-7): ").strip()

        if user_input == "1":
            teacher_profile(current_user_id, lists[3], role, lists[5], teacher_module_id, files, lists)

        elif user_input == "2":
            teacher_module_management(lists[5], teacher_module_id, lists[13], lists, current_user_id, lists[6], files,
                                      lists[11], lists[12])

        elif user_input == "3":
            teacher_student_enrolment(teacher_module_id, lists[2], lists[5], lists[7])

        elif user_input == "4":
            teacher_grading_assessment(teacher_module_id, lists[6], lists[2], lists[8], current_user_id, lists[9],
                                       files, lists, lists[10])

        elif user_input == "5":
            teacher_track_attendance(teacher_module_id, lists[2])

        elif user_input == "6":
            teacher_generate_report(teacher_module_id, lists[5], lists[2], lists[9], lists[10], lists[8], lists[6])

        elif user_input == "7":
            return

        else:
            print("\nPlease insert a valid number (1-7).")