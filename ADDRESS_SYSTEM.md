# Simple Address Management System

## Overview

The address management system allows users to store multiple shipping addresses and designate default addresses for US and UK to streamline the checkout process.

## Features

### ✅ Core Functionality

-   **Multiple Address Storage**: Users can store up to 12 addresses
-   **Country-Specific Defaults**: Support for US and UK default addresses
-   **Automatic Selection**: Default addresses are automatically selected during checkout
-   **Manual Override**: Users can choose different addresses during checkout

### ✅ Address Types

-   **US Default**: Default address for US operations
-   **UK Default**: Default address for UK operations
-   **Combined**: Same address can be default for both US and UK

## Database Schema

### UserAddress Model Fields

```php
- id (Primary Key)
- user_id (Foreign Key to User)
- address_name (String) - User-friendly name for the address
- full_name (String) - Recipient's full name
- address_line_1 (String) - Primary address line
- address_line_2 (String, Nullable) - Secondary address line
- country (String) - Country name
- state (String, Nullable) - State/Province
- city (String) - City name
- postal_code (String) - Postal/ZIP code
- country_code (String) - Country calling code
- phone_number (String) - Contact phone number
- is_default_us (Boolean) - Default for US operations
- is_default_uk (Boolean) - Default for UK operations
- created_at, updated_at (Timestamps)
```

## API Endpoints

### Address Management

```
GET    /customer/account-setting/address-book     # List all addresses
POST   /customer/account-setting/addresses        # Create new address
PUT    /customer/account-setting/addresses/{id}   # Update address
DELETE /customer/account-setting/addresses/{id}   # Delete address
PUT    /customer/account-setting/addresses/{id}/set-default  # Set default
```

### Set Default Address

**Request:**

```json
{
    "type": "us|uk|both"
}
```

**Response:**

-   Success: Redirect with success message
-   Error: Redirect with validation errors

## Usage Examples

### Setting Default Addresses

```php
// Set as default for US
$address->update(['is_default_us' => true]);

// Set as default for UK
$address->update(['is_default_uk' => true]);

// Set as default for both US and UK
$address->update([
    'is_default_us' => true,
    'is_default_uk' => true
]);
```

### Getting Default Addresses

```php
// Get user's default US address
$usAddress = $user->defaultUsAddress;

// Get user's default UK address
$ukAddress = $user->defaultUkAddress;

// Get all default addresses
$defaultAddresses = $user->defaultAddresses;
```

### Checking Address Status

```php
// Check if address is default for any type
$isDefault = $address->isDefault();

// Get default types for an address
$types = $address->getDefaultTypes(); // ['shipping', 'billing']

// Get display text
$text = $address->getDefaultTypeText(); // "Shipping & Billing"
```

## Business Rules

### ✅ Validation Rules

-   **Address Limit**: Maximum 12 addresses per user
-   **Required Fields**: address_name, full_name, address_line_1, country, city, postal_code, country_code, phone_number
-   **Phone Format**: Must contain only numbers, spaces, hyphens, parentheses, and plus signs
-   **Default Types**: Only one default per type per user (us, uk)

### ✅ Default Address Logic

1. **Single Default**: Only one address can be default for each type (us, uk)
2. **Automatic Unsetting**: When setting a new default, previous defaults are automatically unset
3. **Combined Defaults**: An address can be default for both US and UK
4. **Country-Specific**: US and UK defaults are independent of each other
5. **No Auto-Assignment**: Deleting a default address doesn't automatically assign another

### ✅ Security

-   **Authorization**: Users can only manage their own addresses
-   **Route Protection**: All endpoints require authentication and customer middleware
-   **Data Validation**: All input is validated using Form Request classes

## Frontend Features

### ✅ User Interface

-   **Address Cards**: Clean, responsive display of address information
-   **Action Buttons**: Edit and delete options for each address
-   **Default Dropdown**: Simple selection of US/UK defaults
-   **Status Badges**: Visual indicators for US/UK defaults
-   **Modal Forms**: Inline editing with validation feedback
-   **Confirmation Dialogs**: Safe deletion with confirmation

### ✅ User Experience

-   **Real-time Validation**: Form validation with immediate feedback
-   **Loading States**: Visual feedback during form submission
-   **Success Messages**: Clear confirmation of successful actions
-   **Error Handling**: User-friendly error messages
-   **Responsive Design**: Works on all device sizes

## Integration Points

### ✅ Checkout Process

-   **Automatic Selection**: Default addresses are pre-selected
-   **Manual Override**: Users can choose different addresses
-   **Address Validation**: Ensures addresses are valid for shipping/billing

### ✅ Order Management

-   **Address Tracking**: Orders are linked to specific addresses
-   **Address History**: Maintains address information at time of order
-   **Shipping Labels**: Uses address data for label generation

## Future Enhancements

### 🔮 Potential Features

-   **Address Verification**: Integration with address validation services
-   **International Support**: Enhanced support for international addresses
-   **Address Templates**: Pre-defined address formats for different countries
-   **Bulk Operations**: Import/export address data
-   **Address Analytics**: Usage statistics and insights
