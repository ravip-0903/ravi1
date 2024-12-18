<?php
/*
 *
 * Version 1.0
 *
 * ----------------- Disclaimer ------------------------------------------------
 *
 * Copyright © 2007 Dialect Payment Technologies - a Transaction Network
 * Services company.  All rights reserved.
 *
 * This program is provided by Dialect Payment Technologies on the basis that
 * you will treat it as confidential.
 *
 * No part of this program may be reproduced or copied in any form by any means
 * without the written permission of Dialect Payment Technologies.  Unless
 * otherwise expressly agreed in writing, the information contained in this
 * program is subject to change without notice and Dialect Payment Technologies
 * assumes no responsibility for any alteration to, or any error or other
 * deficiency, in this program.
 *
 * 1. All intellectual property rights in the program and in all extracts and 
 *    things derived from any part of the program are owned by Dialect and will 
 *    be assigned to Dialect on their creation. You will protect all the 
 *    intellectual property rights relating to the program in a manner that is 
 *    equal to the protection you provide your own intellectual property.  You 
 *    will notify Dialect immediately, and in writing where you become aware of 
 *    a breach of Dialect's intellectual property rights in relation to the
 *    program.
 * 2. The names "Dialect", "QSI Payments" and all similar words are trademarks
 *    of Dialect Payment Technologies and you must not use that name or any 
 *    similar name.
 * 3. Dialect may at its sole discretion terminate the rights granted in this 
 *    program with immediate effect by notifying you in writing and you will 
 *    thereupon return (or destroy and certify that destruction to Dialect) all 
 *    copies and extracts of the program in its possession or control.
 * 4. Dialect does not warrant the accuracy or completeness of the program or  
 *    its content or its usefulness to you or your merchant customers.  To the  
 *    extent permitted by law, all conditions and warranties implied by law  
 *    (whether as to fitness for any particular purpose or otherwise) are  
 *    excluded. Where the exclusion is not effective, Dialect limits its  
 *    liability to $100 or the resupply of the program (at Dialect's option).
 * 5. Data used in examples and sample data files are intended to be fictional 
 *    and any resemblance to real persons or companies is entirely coincidental.
 * 6. Dialect does not indemnify you or any third party in relation to the
 *   content or any use of the content as contemplated in these terms and 
 *    conditions. 
 * 7. Mention of any product not owned by Dialect does not constitute an 
 *    endorsement of that product.
 * 8. This program is governed by the laws of New South Wales, Australia and is 
 *    intended to be legally binding. 
 * ---------------------------------------------------------------------------*/

/**
 * Please refer to the following guides for more information:
 *     1. Payment Client Integration Guide
 *        this details how to integrate with Payment Client 3.1.
 *     2. Payment Client Reference Guide
 *        this guide details all the input and return parameters that are used
 *        by the Payment Client and Payment Server for a Payment Client
 *        integration.
 *     3. Payment Client Install Guide
 *        this guide details the installation of Payment Client 3.1 and related
 *        issues.
 *
 * @author Dialect Payment Technologies
 *
 */
 
//  ----------------------------------------------------------------------------

// This function uses the QSI Response code retrieved from the Digital
// Receipt and returns an appropriate description for the QSI Response Code
//
// @param $responseCode String containing the QSI Response Code
//
// @return String containing the appropriate description
//
function getResultDescription($responseCode) {

    switch ($responseCode) {
        case "0" : $result = "Transaction Successful"; break;
        case "?" : $result = "Transaction status is unknown"; break;
        case "E" : $result = "Referred"; break;
        case "1" : $result = "Transaction Declined"; break;
        case "2" : $result = "Bank Declined Transaction"; break;
        case "3" : $result = "No Reply from Bank"; break;
        case "4" : $result = "Expired Card"; break;
        case "5" : $result = "Insufficient funds"; break;
        case "6" : $result = "Error Communicating with Bank"; break;
        case "7" : $result = "Payment Server detected an error"; break;
        case "8" : $result = "Transaction Type Not Supported"; break;
        case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
        case "A" : $result = "Transaction Aborted"; break;
        case "C" : $result = "Transaction Cancelled"; break;
        case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
        case "F" : $result = "3D Secure Authentication failed"; break;
        case "I" : $result = "Card Security Code verification failed"; break;
        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
        case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
        case "S" : $result = "Duplicate SessionID (Amex Only)"; break;
        case "T" : $result = "Address Verification Failed"; break;
        case "U" : $result = "Card Security Code Failed"; break;
        case "V" : $result = "Address Verification and Card Security Code Failed"; break;
        default  : $result = "Unable to be determined"; 
    }
    return $result;
}

//  ----------------------------------------------------------------------------

// This function uses the QSI AVS Result Code retrieved from the Digital
// Receipt and returns an appropriate description for this code.

// @param avsResultCode String containing the QSI AVS Result Code
// @return description String containing the appropriate description

function getAVSResultDescription($avsResultCode) {
    
    if ($avsResultCode != "") { 
        switch ($avsResultCode) {
            Case "Unsupported" : $result = "AVS not supported or there was no AVS data provided"; break;
            Case "X"  : $result = "Exact match - address and 9 digit ZIP/postal code"; break;
            Case "Y"  : $result = "Exact match - address and 5 digit ZIP/postal code"; break;
            Case "S"  : $result = "Service not supported or address not verified (international transaction)"; break;
            Case "G"  : $result = "Issuer does not participate in AVS (international transaction)"; break;
            Case "A"  : $result = "Address match only"; break;
            Case "W"  : $result = "9 digit ZIP/postal code matched, Address not Matched"; break;
            Case "Z"  : $result = "5 digit ZIP/postal code matched, Address not Matched"; break;
            Case "R"  : $result = "Issuer system is unavailable"; break;
            Case "U"  : $result = "Address unavailable or not verified"; break;
            Case "E"  : $result = "Address and ZIP/postal code not provided"; break;
            Case "N"  : $result = "Address and ZIP/postal code not matched"; break;
            Case "0"  : $result = "AVS not requested"; break;
            default   : $result = "Unable to be determined"; 
        }
    } else {
        $result = "null response";
    }
    return $result;
}

//  ----------------------------------------------------------------------------

// This function uses the QSI CSC Result Code retrieved from the Digital
// Receipt and returns an appropriate description for this code.

// @param cscResultCode String containing the QSI CSC Result Code
// @return description String containing the appropriate description

function getCSCResultDescription($cscResultCode) {
    
    if ($cscResultCode != "") {
        switch ($cscResultCode) {
            Case "Unsupported" : $result = "CSC not supported or there was no CSC data provided"; break;
            Case "M"  : $result = "Exact code match"; break;
            Case "S"  : $result = "Merchant has indicated that CSC is not present on the card (MOTO situation)"; break;
            Case "P"  : $result = "Code not processed"; break;
            Case "U"  : $result = "Card issuer is not registered and/or certified"; break;
            Case "N"  : $result = "Code invalid or not matched"; break;
            default   : $result = "Unable to be determined"; break;
        }
    } else {
        $result = "null response";
    }
    return $result;
}

//  -----------------------------------------------------------------------------


?>