# HOSTELCONNECT PROJECT RISK REGISTER

## 1.1 Introduction

The Project Risk Register identifies potential problems that could affect the successful development, testing, deployment and submission of HostelConnect.

Its purpose is to help the project team identify risks early, evaluate their possible effects and establish appropriate measures to prevent or minimise their impact.

Since HostelConnect involves user management, hostel discovery, room booking, payment processing and database management, problems in one area could affect the overall performance and reliability of the system.

The Risk Register will be reviewed and updated throughout the project lifecycle.

## 1.2 Risk Assessment Methodology

Each identified risk is evaluated using three main factors:

- **Likelihood:** The probability that the risk will occur.
- **Impact:** The level of damage or disruption the risk could cause.
- **Risk Priority:** The urgency with which the risk should be addressed.

The following rating levels are used:

| Rating | Description |
|---|---|
| Low | The risk has a minor effect and can be managed easily. |
| Medium | The risk may affect project progress or system functionality and requires attention. |
| High | The risk could significantly affect project delivery, security or system reliability. |

Risk priority is determined by considering both likelihood and impact.

## 1.3 Identified Project Risks and Mitigation Strategies

The following risks have been identified for HostelConnect.

| ID | Identified Risk | Likelihood | Impact | Priority |
|---|---|---|---|---|
| R01 | Late completion of assigned tasks | Medium | High | High |
| R02 | Git branch conflicts and divergent changes | Medium | Medium | Medium |
| R03 | Database schema and PHP code mismatch | Medium | High | High |
| R04 | Overlapping room bookings | Medium | High | High |
| R05 | Incorrect semester-based booking prices | Medium | High | High |
| R06 | Paystack Sandbox unavailability | Medium | Medium | Medium |
| R07 | Invalid payment verification | Medium | High | High |
| R08 | Unauthorised access | Medium | High | High |
| R09 | Exposure of passwords and sensitive credentials | Medium | High | High |
| R10 | Loss of project or database data | Low | High | Medium |
| R11 | Differences between XAMPP and online hosting | Medium | Medium | Medium |
| R12 | Insufficient software testing | Medium | High | High |
| R13 | Inconsistency between documentation and application | Medium | Medium | Medium |
| R14 | Limited development resources | Medium | Medium | Medium |
| R15 | Expansion beyond the project scope | Medium | Medium | Medium |
| R16 | Mobile and browser compatibility problems | Medium | Medium | Medium |
| R17 | Inaccurate hostel information and room availability | Medium | High | High |
| R18 | Problems during team role rotation | Medium | Medium | Medium |

*Note: These are initial qualitative assessments. The team should review and adjust them using actual project experience.*

## 1.4 Risk Mitigation and Response Plan

### R01: Late Completion of Assigned Tasks

**Description:** Team members may fail to complete their assigned responsibilities within the agreed timeline, causing delays in other project activities.

**Mitigation:**
- Assign responsibilities clearly.
- Establish realistic deadlines.
- Monitor progress during sprint reviews.
- Communicate delays early.
- Reassign tasks where necessary.

**Responsible:** Project Manager.

### R02: Git Branch Conflicts and Divergent Changes

**Description:** Multiple developers may edit the same files, resulting in conflicts during GitHub integration.

**Mitigation:**
- Use feature branches for individual contributions.
- Submit pull requests for review.
- Avoid unnecessary direct changes to the main branch.
- Communicate before modifying shared files.
- Resolve conflicts carefully before merging.

**Responsible:** Lead Developer and Project Manager.

### R03: Database Schema and PHP Code Mismatch

**Description:** Changes to database tables or columns may affect existing PHP code and cause application errors.

**Mitigation:**
- Maintain an updated database schema.
- Communicate database changes to developers.
- Test database modifications.
- Keep SQL scripts updated.
- Verify database relationships.

**Responsible:** Lead Developer.

### R04: Overlapping Room Bookings

**Description:** Two or more students may attempt to reserve the same room for overlapping booking periods.

**Mitigation:**
- Check room availability before confirming bookings.
- Validate booking dates.
- Prevent conflicting reservations.
- Use appropriate database transactions and constraints where applicable.
- Test simultaneous booking scenarios.

**Responsible:** Lead Developer and Tester.

### R05: Incorrect Semester-Based Booking Prices

**Description:** Differences between displayed prices, booking records and payment amounts may result in incorrect transactions.

**Mitigation:**
- Maintain consistent semester-based pricing.
- Validate booking amounts on the server.
- Retrieve prices from trusted database records.
- Test booking calculations.
- Verify the amount before payment processing.

**Responsible:** Lead Developer and Tester.

### R06: Paystack Sandbox Unavailability

**Description:** Temporary service interruptions or unexpected responses may affect payment testing.

**Mitigation:**
- Use Paystack Sandbox for simulated transactions.
- Handle failed and incomplete payment responses.
- Provide appropriate error messages.
- Repeat testing when the service becomes available.

**Responsible:** Lead Developer.

### R07: Invalid Payment Verification

**Description:** A booking may incorrectly be marked as paid without successful payment confirmation.

**Mitigation:**
- Verify transactions before updating payment status.
- Validate transaction references.
- Handle failed and pending transactions correctly.
- Prevent unverified payment responses from confirming bookings.
- Record payment results accurately.

**Responsible:** Lead Developer and Tester.

### R08: Unauthorised Access

**Description:** Users may access functions that are not intended for their assigned roles.

**Mitigation:**
- Implement server-side role-based access control.
- Validate user sessions.
- Restrict protected pages.
- Test access permissions for different user roles.

**Responsible:** Lead Developer.

### R09: Exposure of Passwords and Sensitive Credentials

**Description:** Passwords, database credentials and payment keys may be exposed through insecure storage or GitHub commits.

**Mitigation:**
- Hash user passwords securely.
- Protect database credentials.
- Keep sensitive payment keys out of public repositories.
- Use environment variables or protected configuration files.
- Review commits before pushing them to GitHub.

**Responsible:** Lead Developer.

### R10: Loss of Project or Database Data

**Description:** Accidental deletion, computer failure or database corruption may result in lost project work.

**Mitigation:**
- Maintain regular database backups.
- Push completed work to GitHub.
- Keep copies of important SQL scripts.
- Avoid deleting database files without proper backups.

**Responsible:** Project Manager and Lead Developer.

### R11: Differences Between XAMPP and Online Hosting

**Description:** The application may behave differently after deployment because of differences in server configurations.

**Mitigation:**
- Review hosting requirements.
- Check PHP and database compatibility.
- Test application configuration before deployment.
- Verify database connections in the hosting environment.

**Responsible:** Lead Developer.

### R12: Insufficient Software Testing

**Description:** Inadequate testing may allow errors to remain in the application.

**Mitigation:**
- Prepare a structured test plan.
- Test all major system functions.
- Record test results.
- Include at least one automated test.
- Retest corrected defects.

**Responsible:** Tester and Lead Developer.

### R13: Inconsistency Between Documentation and Application

**Description:** Project documents may describe features or processes that do not match the actual application.

**Mitigation:**
- Inspect the implemented application.
- Verify documentation against actual functionality.
- Update diagrams when system designs change.
- Review documents before final submission.

**Responsible:** Documentation Lead.

### R14: Limited Development Resources

**Description:** Poor internet access, limited laptop availability or software challenges may affect team contributions.

**Mitigation:**
- Coordinate available resources.
- Share project updates regularly.
- Plan tasks according to available resources.
- Communicate technical difficulties early.

**Responsible:** Project Manager.

### R15: Expansion Beyond the Project Scope

**Description:** Adding unnecessary features may delay development and affect project completion.

**Mitigation:**
- Maintain the agreed project scope.
- Prioritise the four core features.
- Evaluate additional features before accepting them.
- Focus on completing required deliverables.

**Responsible:** Project Manager and Lead Developer.

### R16: Mobile and Browser Compatibility Problems

**Description:** Some pages may not display correctly on different devices or browsers.

**Mitigation:**
- Use responsive interface design.
- Test important pages on different screen sizes.
- Check browser compatibility.
- Correct identified layout problems.

**Responsible:** Lead Developer and Tester.

### R17: Inaccurate Hostel Information and Room Availability

**Description:** Incorrect hostel details, room availability or semester prices may mislead students.

**Mitigation:**
- Validate hostel information.
- Allow authorised hostel owners to update listings.
- Maintain accurate room availability records.
- Check information before publishing listings.

**Responsible:** Lead Developer and Project Manager.

### R18: Problems During Team Role Rotation

**Description:** Poor communication during the three-week role rotation may result in lost information or unfinished responsibilities.

**Mitigation:**
- Document completed activities.
- Conduct proper handovers.
- Share project progress with team members.
- Maintain updated GitHub records.
- Clarify responsibilities after each rotation.

**Responsible:** Project Manager and all team members.

## 1.5 Risk Monitoring and Control

The Project Manager coordinates risk monitoring throughout the project.

During sprint planning and reviews, the team should:

1. Review previously identified risks.
2. Identify newly emerging risks.
3. Evaluate changes in likelihood and impact.
4. Check whether mitigation measures are effective.
5. Record problems that have occurred.
6. Assign corrective actions to responsible members.

Risks should be updated whenever significant changes occur in project development, testing or integration.

## 1.6 Risk Status Tracking

Each risk should have a status indicating its current condition.

| Status | Description |
|---|---|
| Open | The risk has been identified but no response has been completed. |
| Monitoring | The risk is being actively observed. |
| Mitigated | Measures have been introduced to reduce the risk. |
| Occurred | The risk has become an actual problem. |
| Closed | The risk is no longer active or requires further action. |

The team should record the review date, responsible person, mitigation actions and supporting evidence when updating risk statuses.

## 1.7 Relationship Between the Risk Register and Sprint Plans

The Risk Register and Sprint Plans support each other throughout the HostelConnect project.

The Sprint Plans describe project activities, timelines, responsibilities and expected deliverables, while the Risk Register identifies possible challenges and the measures for managing them.

For example, during system development, the team must address the risk of overlapping room bookings. During payment integration, it must also address the possibility of incorrect payment verification.

Together, these documents help the team organise its work, manage potential problems and improve the successful delivery of HostelConnect.

## 1.8 Conclusion

The HostelConnect Project Risk Register provides a structured approach to identifying, evaluating and managing challenges that could affect the project.

It focuses on important areas such as team coordination, GitHub collaboration, database integration, booking accuracy, payment processing, security and software testing.

Regular risk monitoring, clear responsibilities and appropriate preventive measures will help the team minimise project difficulties and improve the successful delivery of HostelConnect.