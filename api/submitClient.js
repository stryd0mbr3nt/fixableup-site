// /api/submitClient.js

import { createClient } from '@supabase/supabase-js'

// Initialize Supabase client
const supabaseUrl = process.env.SUPABASE_URL
const supabaseKey = process.env.SUPABASE_ANON_KEY
const supabase = createClient(supabaseUrl, supabaseKey)

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).send({ message: 'Only POST allowed' })
  }

  const { name, email, location, service, details } = req.body

  // Basic validation
  if (!name || !email || !location || !service) {
    return res.status(400).json({ message: 'Missing required fields' })
  }

  // Insert into Supabase
  const { error } = await supabase.from('clients').insert([
    { name, email, location, service, details }
  ])

  if (error) {
    console.error(error)
    return res.status(500).json({ message: 'Database error' })
  }

  // Send email to admin (using Resend API or Supabase SMTP)
  await sendEmailToAdmin(name, email, location, service, details)
  await sendEmailToClient(name, email)

  return res.status(200).json({ message: 'Success' })
}

// --- Email Sending ---
async function sendEmailToAdmin(name, email, location, service, details) {
  // Use Resend API, Postmark, or Supabase SMTP here
  console.log(`Send admin email: ${name} | ${email} | ${service}`)
}

async function sendEmailToClient(name, email) {
  // Use Resend API, Postmark, or Supabase SMTP here
  console.log(`Send client confirmation email to ${email}`)
}
