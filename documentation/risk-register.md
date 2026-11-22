# 1. HOSTELCONNECT PROJECT RISK REGISTER

## 1.1 Introduction

The Project Risk Register identifies possible problems that could affect the successful development, testing and submission of HostelConnect.

Its purpose is to help the team identify risks early, understand their possible effects and establish measures to prevent or reduce them.

Since HostelConnect involves user management, hostel discovery, room booking, payment processing and database management, problems in one area could affect the entire system.

The risk register should therefore be reviewed and updated throughout the project.

## 1.2 Risk Assessment

Risks are assessed using three main factors:

- **Likelihood:** The possibility of a risk occurring.
- **Impact:** The level of damage or disruption the risk could cause.
- **Priority:** How urgently the team needs to address the risk.

High-priority risks require more attention because they could seriously affect the project's success.

## 1.3 Identified Project Risks

The following risks have been identified for HostelConnect:

1. **Late Completion of Assigned Tasks:** Team members may fail to complete their responsibilities on time, causing delays in other project activities.

2. **Git Branch Conflicts and Divergent Changes:** Multiple developers may edit the same files, resulting in conflicts during GitHub integration. Feature branches, pull requests and proper communication will help reduce this risk.

3. **Database Schema and PHP Code Mismatch:** Changes to database tables or columns may affect the PHP code, causing errors. Database changes must be communicated, documented and tested.

4. **Overlapping Room Bookings:** Two students may attempt to reserve the same room for overlapping periods. The system must check room availability before confirming bookings.

5. **Incorrect Semester-Based Booking Prices:** Differences between displayed prices, booking records and payment amounts may result in incorrect transactions. The system must maintain consistent semester pricing.

6. **Paystack Sandbox Unavailability:** Temporary service interruptions or unexpected responses may affect payment testing. The application should handle different payment responses appropriately.

7. **Invalid Payment Verification:** A booking may incorrectly be marked as paid without successful payment confirmation. Payment transactions must be verified before updating their status.

8. **Unauthorised Access:** Users may access functions that are not intended for their roles. Server-side role-based access control must be implemented.

9. **Exposure of Passwords and Sensitive Credentials:** Passwords, database credentials and payment keys may be exposed through insecure storage or GitHub commits. Passwords must be securely hashed, and confidential credentials must be protected.

10. **Loss of Project or Database Data:** Accidental deletion, computer failure or database corruption may result in lost work. Regular backups of project files and database records are necessary.

11. **Differences Between XAMPP and Online Hosting:** The application may behave differently after deployment because of differences in server configurations. The hosting environment must be tested before deployment.

12. **Insufficient Software Testing:** Inadequate testing may allow errors to remain in the application. The team must test the main system functions and include at least one automated test.

13. **Inconsistency Between Documentation and Application:** Project documents may describe features that do not match the actual system. All documentation must reflect the implemented application.

14. **Limited Development Resources:** Poor internet access, limited laptop availability or software challenges may affect members' contributions. Tasks and resources should be coordinated properly.

15. **Expansion Beyond the Project Scope:** Adding unnecessary features may delay development and affect completion. The team must maintain the four agreed core features.

16. **Mobile and Browser Compatibility Problems:** Some pages may not display correctly on different devices or browsers. The application should be tested on different screen sizes.

17. **Inaccurate Hostel Information and Room Availability:** Incorrect hostel details, room availability or semester prices may mislead students. Information should be properly maintained and validated.

18. **Problems During Team Role Rotation:** Poor communication during the three-week role rotation may result in lost information or unfinished responsibilities. Proper documentation and handover are necessary.

## 1.4 Risk Monitoring

The Project Manager is responsible for ensuring that identified risks are monitored throughout the project.

During sprint planning and reviews, the team should examine possible risks, assess their effects and determine whether additional preventive measures are required.

When a risk occurs, the team should record the problem, the corrective action taken and the lessons learned.

## 1.5 Risk Status Record

Each risk should have a status to show its current condition.

The main statuses are:

- **Open:** The risk has been identified but is not yet resolved.
- **Monitoring:** The risk is being actively observed.
- **Mitigated:** Measures have been introduced to reduce the risk.
- **Occurred:** The risk has become an actual problem.
- **Closed:** The risk is no longer active.

The team should also record the review date, responsible person, actions taken and supporting evidence.

## 1.6 Conclusion

The HostelConnect Risk Register provides a structured approach to identifying and managing challenges that could affect the project.

It focuses on important areas such as team coordination, GitHub collaboration, database integration, booking accuracy, payment processing, security and software testing.

Regular risk monitoring and appropriate preventive measures will help the team reduce project difficulties and improve the successful delivery of HostelConnect.