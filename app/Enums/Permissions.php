<?php

namespace App\Enums;

enum Permissions: string
{

    case CREATE_SUBSCRIPTION = 'create.Subscription';
    case BILL_STUDENTS = 'bill.Student';

    case VIEW_DEBTS = 'view.Debt';
    case VIEW_REPORTS = 'view.Report';
    case ASSIGN_ROLE = 'assign.Role';
    case ASSIGN_SUBJECT = 'assign.Subject';
    case ASSIGN_STAFF = 'assign.Staff';
    case ASSIGN_TRANSPORTATION = 'assign.Transportation';
    case ISSUE_BOOK = 'issue.Book';
    case RETURN_BOOK = 'return.Book';
    case BOOK_HISTORY = 'book.History';
    case SEND_NOTIFICATION = 'send.Notification';
    case EDIT_SCHOOL = 'edit.School';
    case VIEW_LOG = 'view.Log';
    case VIEW_TRASH = 'view.Trash';
    case VIEW_TERMINAL_REPORT = 'view.TerminalReport';
    case FINANCIAL_REPORT = 'financial.Report';
    case CREATE_ALLOWANCE = 'create.Allowance';
    case EDIT_ALLOWANCE  = 'edit.Allowance';

    // STAFF
    case STAFF_ATTENDANCE = 'staff.Attendance';
    case VIEW_DASHBOARD = 'view.Dashboard';
    case DELETE_ALLOWANCE = 'delete.AllowanceAndDeduction';

    case ASSIGN_PERMISSION = 'assign.Permission';

}
