# Data Permissions Display Feature - Offcanvas Implementation

## Overview
This feature displays the selected application data permissions for each application on the user dashboards across all portals (Student, Institution, Ministry). It uses a clean button interface that opens a detailed Bootstrap offcanvas sidebar showing data access permissions in an organized, user-friendly format.

## Implementation

### Shared Component
Created `resources/js/Components/DataPermissionsOffcanvas.vue` - a reusable component that all modules can import and use.

#### Component Features
- **Responsive offcanvas sidebar** (420px on desktop, full width on mobile)
- **Categorized data display** with table-specific icons and colors
- **Permission details** showing individual fields with read/write indicators
- **Clean typography** with clear hierarchy and spacing
- **Portal-agnostic** design that works across all dashboard types

### Backend Changes

#### Controllers Updated
- `Modules/Student/app/Http/Controllers/StudentController.php`
- `Modules/Institution/app/Http/Controllers/InstitutionController.php`
- `Modules/Ministry/app/Http/Controllers/MinistryController.php`

#### Key Changes
1. **Added relationship loading**: Added `->with('dataPermissions')` to application queries
2. **Added permission grouping**: Group permissions by table name for better organization
3. **Added helper method**: `getTableLabel()` to get user-friendly table names

#### Data Structure
The applications now include a `data_permission_groups` array with the following structure:
```php
[
    'table_name' => 'individuals',
    'table_label' => 'Individual Profile', 
    'permissions' => [
        [
            'column_name' => 'first_name',
            'display_name' => 'First Name',
            'can_read' => true,
            'can_write' => false
        ],
        // ... more permissions
    ]
]
```

### Frontend Changes

#### Vue Components Updated
- `Modules/Student/resources/assets/js/Pages/Dashboard.vue`
- `Modules/Institution/resources/assets/js/Pages/Dashboard.vue`
- `Modules/Ministry/resources/assets/js/Pages/Dashboard.vue`

#### UI/UX Design

##### Dashboard Display (Clean & Simple)
- **Data Access Permissions** section with clear label
- **Single button** showing total permission count
- **Portal-specific styling**:
  - **Student**: Green outline button (`btn-outline-success`)
  - **Institution**: Orange outline button (`btn-outline-warning`)  
  - **Ministry**: BC Government blue button with custom styling
- **Clear call-to-action**: "View X permission(s)" with arrow icon

##### Offcanvas Sidebar (Detailed View)
- **Header**: Application name with permissions title
- **Summary alert**: Explains data access clearly
- **Categorized sections**: Each table gets its own card with:
  - **Table icon**: Different icons per data type (person, location, briefcase, etc.)
  - **Color coding**: Consistent with table types
  - **Permission badges**: Individual fields with read/write indicators
  - **Count display**: Number of permissions per category
- **Footer summary**: Total permission count and privacy notice

#### Component Features
```vue
<!-- Example usage in dashboard -->
<button 
  type="button" 
  class="btn btn-outline-success btn-sm"
  data-bs-toggle="offcanvas" 
  :data-bs-target="`#permissions-${app.id}`"
>
  <i class="bi bi-list-ul me-1"></i>
  View {{ getTotalPermissions(app.data_permission_groups) }} permission{{ getTotalPermissions(app.data_permission_groups) !== 1 ? 's' : '' }}
  <i class="bi bi-arrow-right ms-1"></i>
</button>

<!-- Reusable offcanvas component -->
<DataPermissionsOffcanvas
  :offcanvas-id="`permissions-${app.id}`"
  :application-name="app.name"
  :permission-groups="app.data_permission_groups || []"
/>
```

## Benefits

### For Users
1. **Clean Interface**: No visual clutter on main dashboard
2. **On-Demand Details**: Full information available when needed
3. **Clear Understanding**: Organized view of exactly what data is accessed
4. **Trust & Transparency**: See permissions before using applications

### For Developers
1. **Reusable Component**: Single component used across all portals
2. **Consistent Experience**: Same functionality with portal-specific styling
3. **Maintainable Code**: Centralized offcanvas logic
4. **Responsive Design**: Works on all device sizes

### For Administrators
1. **Compliance**: Clear demonstration of data access transparency
2. **User Education**: Helps users understand data sharing
3. **Audit Trail**: Visual record of what permissions are granted

## Technical Implementation

### Shared Component Structure
```
resources/js/Components/DataPermissionsOffcanvas.vue
├── Props: offcanvasId, applicationName, permissionGroups
├── Computed: totalPermissionsCount
├── Methods: getTableIcon, getTableIconClass, formatColumnName
└── Styling: Responsive offcanvas with portal-agnostic design
```

### Data Flow
1. **Backend**: Controllers load applications with `dataPermissions` relationship
2. **Processing**: Group permissions by table for organized display
3. **Frontend**: Simple button triggers offcanvas with detailed view
4. **Component**: Reusable offcanvas renders categorized permissions

### Portal-Specific Customization
- **Student**: Green color scheme (`btn-outline-success`)
- **Institution**: Orange color scheme (`btn-outline-warning`)
- **Ministry**: BC Blue custom styling with hover effects

## Usage Examples

### Example 1: Student Portal
When a student sees an application card:
- Clean button: "View 7 permissions →"
- Click opens sidebar showing: "Individual Profile (5), Addresses (1), Employment (1)"
- Detailed breakdown shows exact fields like "First Name", "Date of Birth", etc.

### Example 2: Institution Portal  
Same functionality with orange institutional branding.

### Example 3: Ministry Portal
Same functionality with BC Government blue branding and custom button styling.

## Testing

### Test Data Available
Sample permissions created for the first application:
- **Individual Profile**: Date of Birth, Social Insurance Number, Last Name, Preferred Name, User GUID
- **Addresses**: Primary Address  
- **Employment**: Current Employer

### Verification Steps
1. Navigate to any portal dashboard
2. Look for applications with "Data Access Permissions" section
3. Click "View X permissions" button
4. Verify offcanvas opens with organized permission display
5. Check portal-specific styling is correct
6. Test responsive behavior on different screen sizes

## Future Enhancements

### Potential Improvements
1. **Permission Filtering**: Allow users to see only read or write permissions
2. **Export Options**: Generate permission summaries for records
3. **Usage Analytics**: Track which permissions users review most
4. **Interactive Consent**: Allow users to modify permissions (if business rules permit)

### Administrative Features
1. **Bulk Permission Display**: Show all applications' permissions in one view
2. **Permission Templates**: Create standard permission sets
3. **Audit Reporting**: Generate reports on permission usage

## Conclusion

This implementation provides an excellent user experience by keeping the dashboard clean while making detailed permission information easily accessible. The reusable component approach ensures consistency across all portals while allowing for portal-specific styling and branding. The offcanvas design pattern is modern, mobile-friendly, and provides ample space for detailed information without cluttering the main interface.
