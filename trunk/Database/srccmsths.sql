SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `SRCCMSTHS` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `SRCCMSTHS` ;

-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Student`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Student` (
  `student_no` CHAR(11) NOT NULL ,
  `year_level` VARCHAR(45) NULL ,
  `learners_no` CHAR(12) NULL ,
  `first_name` VARCHAR(45) NULL ,
  `last_name` VARCHAR(45) NULL ,
  `middle_name` VARCHAR(45) NULL ,
  `date_of_birth` DATE NULL ,
  `gender` CHAR(1) NULL ,
  `year` YEAR NULL ,
  `entry_level` VARCHAR(45) NULL ,
  `exit_level` VARCHAR(45) NULL ,
  `exit_status` VARCHAR(45) NULL ,
  PRIMARY KEY (`student_no`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Applicant`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Applicant` (
  `applicant_no` INT NOT NULL AUTO_INCREMENT ,
  `learners_no` CHAR(12) NULL ,
  `first_name` VARCHAR(45) NULL ,
  `last_name` VARCHAR(45) NULL ,
  `middle_name` VARCHAR(45) NULL ,
  `date_of_birth` DATE NULL ,
  `gender` CHAR(1) NULL ,
  `applicant_honor` TINYINT(1) NULL ,
  `year` YEAR NULL ,
  `exam_grade` INT NULL ,
  `exam_date` DATE NULL ,
  `Student_student_no` CHAR(11) NULL ,
  PRIMARY KEY (`applicant_no`, `Student_student_no`) ,
  INDEX `fk_Applicant_Student1_idx` (`Student_student_no` ASC) ,
  CONSTRAINT `fk_Applicant_Student1`
    FOREIGN KEY (`Student_student_no` )
    REFERENCES `SRCCMSTHS`.`Student` (`student_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Requirement`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Requirement` (
  `requirement_no` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(45) NULL ,
  `year` YEAR NULL ,
  `document` VARCHAR(45) NULL ,
  PRIMARY KEY (`requirement_no`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Applicant_has_Requirement`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Applicant_has_Requirement` (
  `Applicant_applicant_no` INT NOT NULL ,
  `Requirement_requirement_no` INT NOT NULL ,
  `submitted` TINYINT(1) NULL ,
  PRIMARY KEY (`Applicant_applicant_no`, `Requirement_requirement_no`) ,
  INDEX `fk_Applicant_has_Requirement_Requirement1_idx` (`Requirement_requirement_no` ASC) ,
  INDEX `fk_Applicant_has_Requirement_Applicant_idx` (`Applicant_applicant_no` ASC) ,
  CONSTRAINT `fk_Applicant_has_Requirement_Applicant`
    FOREIGN KEY (`Applicant_applicant_no` )
    REFERENCES `SRCCMSTHS`.`Applicant` (`applicant_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Applicant_has_Requirement_Requirement1`
    FOREIGN KEY (`Requirement_requirement_no` )
    REFERENCES `SRCCMSTHS`.`Requirement` (`requirement_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Interviewee`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Interviewee` (
  `interview_no` INT NOT NULL AUTO_INCREMENT ,
  `date` DATE NULL ,
  `interview_grade` INT NULL ,
  `remarks` VARCHAR(255) NULL ,
  `Applicant_applicant_no` INT NOT NULL ,
  PRIMARY KEY (`interview_no`, `Applicant_applicant_no`) ,
  INDEX `fk_Interviewee_Applicant1_idx` (`Applicant_applicant_no` ASC) ,
  CONSTRAINT `fk_Interviewee_Applicant1`
    FOREIGN KEY (`Applicant_applicant_no` )
    REFERENCES `SRCCMSTHS`.`Applicant` (`applicant_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Department`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Department` (
  `dept_no` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`dept_no`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Teacher`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Teacher` (
  `teacher_no` INT NOT NULL AUTO_INCREMENT ,
  `last_name` VARCHAR(45) NULL ,
  `first_name` VARCHAR(45) NULL ,
  `middle_name` VARCHAR(45) NULL ,
  `Department_dept_no` INT NOT NULL ,
  PRIMARY KEY (`teacher_no`, `Department_dept_no`) ,
  INDEX `fk_Teacher_Department1_idx` (`Department_dept_no` ASC) ,
  CONSTRAINT `fk_Teacher_Department1`
    FOREIGN KEY (`Department_dept_no` )
    REFERENCES `SRCCMSTHS`.`Department` (`dept_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Subject`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Subject` (
  `subject_no` INT NOT NULL AUTO_INCREMENT ,
  `code` VARCHAR(45) NULL ,
  `name` VARCHAR(45) NULL ,
  `year` YEAR NULL ,
  PRIMARY KEY (`subject_no`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Section`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Section` (
  `section_no` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `year` YEAR NULL ,
  `Teacher_teacher_no` INT NOT NULL ,
  `Teacher_Department_dept_no` INT NOT NULL ,
  PRIMARY KEY (`section_no`, `Teacher_teacher_no`, `Teacher_Department_dept_no`) ,
  INDEX `fk_Section_Teacher1_idx` (`Teacher_teacher_no` ASC, `Teacher_Department_dept_no` ASC) ,
  CONSTRAINT `fk_Section_Teacher1`
    FOREIGN KEY (`Teacher_teacher_no` , `Teacher_Department_dept_no` )
    REFERENCES `SRCCMSTHS`.`Teacher` (`teacher_no` , `Department_dept_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`StudentSection`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`StudentSection` (
  `Student_student_no` CHAR(11) NOT NULL ,
  `Section_section_no` INT NOT NULL ,
  `year` YEAR NULL ,
  PRIMARY KEY (`Student_student_no`, `Section_section_no`) ,
  INDEX `fk_Student_has_Section_Section1_idx` (`Section_section_no` ASC) ,
  INDEX `fk_Student_has_Section_Student1_idx` (`Student_student_no` ASC) ,
  CONSTRAINT `fk_Student_has_Section_Student1`
    FOREIGN KEY (`Student_student_no` )
    REFERENCES `SRCCMSTHS`.`Student` (`student_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Student_has_Section_Section1`
    FOREIGN KEY (`Section_section_no` )
    REFERENCES `SRCCMSTHS`.`Section` (`section_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`SubjectStudentSection`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`SubjectStudentSection` (
  `Subject_subject_no` INT NOT NULL ,
  `StudentSection_Student_student_no` CHAR(11) NOT NULL ,
  `StudentSection_Section_section_no` INT NOT NULL ,
  PRIMARY KEY (`Subject_subject_no`, `StudentSection_Student_student_no`, `StudentSection_Section_section_no`) ,
  INDEX `fk_Subject_has_Student_has_Section_Subject1_idx` (`Subject_subject_no` ASC) ,
  INDEX `fk_SubjectStudentSection_StudentSection1_idx` (`StudentSection_Student_student_no` ASC, `StudentSection_Section_section_no` ASC) ,
  CONSTRAINT `fk_Subject_has_Student_has_Section_Subject1`
    FOREIGN KEY (`Subject_subject_no` )
    REFERENCES `SRCCMSTHS`.`Subject` (`subject_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_SubjectStudentSection_StudentSection1`
    FOREIGN KEY (`StudentSection_Student_student_no` , `StudentSection_Section_section_no` )
    REFERENCES `SRCCMSTHS`.`StudentSection` (`Student_student_no` , `Section_section_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Teacher_has_Subject`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Teacher_has_Subject` (
  `Teacher_teacher_no` INT NOT NULL ,
  `Teacher_Department_dept_no` INT NOT NULL ,
  `Subject_subject_no` INT NOT NULL ,
  `year` YEAR NULL ,
  PRIMARY KEY (`Teacher_teacher_no`, `Teacher_Department_dept_no`, `Subject_subject_no`) ,
  INDEX `fk_Teacher_has_Subject_Subject1_idx` (`Subject_subject_no` ASC) ,
  INDEX `fk_Teacher_has_Subject_Teacher1_idx` (`Teacher_teacher_no` ASC, `Teacher_Department_dept_no` ASC) ,
  CONSTRAINT `fk_Teacher_has_Subject_Teacher1`
    FOREIGN KEY (`Teacher_teacher_no` , `Teacher_Department_dept_no` )
    REFERENCES `SRCCMSTHS`.`Teacher` (`teacher_no` , `Department_dept_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Teacher_has_Subject_Subject1`
    FOREIGN KEY (`Subject_subject_no` )
    REFERENCES `SRCCMSTHS`.`Subject` (`subject_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SRCCMSTHS`.`Grades`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SRCCMSTHS`.`Grades` (
  `quarter` CHAR(1) NOT NULL ,
  `grade` DECIMAL(5,2) NULL ,
  `letter_equiv` CHAR(2) NULL ,
  `SubjectStudentSection_Subject_subject_no` INT NOT NULL ,
  `SubjectStudentSection_StudentSection_Student_student_no` CHAR(11) NOT NULL ,
  `SubjectStudentSection_StudentSection_Section_section_no` INT NOT NULL ,
  PRIMARY KEY (`quarter`, `SubjectStudentSection_Subject_subject_no`, `SubjectStudentSection_StudentSection_Student_student_no`, `SubjectStudentSection_StudentSection_Section_section_no`) ,
  INDEX `fk_Grades_SubjectStudentSection1_idx` (`SubjectStudentSection_Subject_subject_no` ASC, `SubjectStudentSection_StudentSection_Student_student_no` ASC, `SubjectStudentSection_StudentSection_Section_section_no` ASC) ,
  CONSTRAINT `fk_Grades_SubjectStudentSection1`
    FOREIGN KEY (`SubjectStudentSection_Subject_subject_no` , `SubjectStudentSection_StudentSection_Student_student_no` , `SubjectStudentSection_StudentSection_Section_section_no` )
    REFERENCES `SRCCMSTHS`.`SubjectStudentSection` (`Subject_subject_no` , `StudentSection_Student_student_no` , `StudentSection_Section_section_no` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
