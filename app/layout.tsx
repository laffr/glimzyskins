import { Metadata } from "next";
import "./globals.css"

export const metadata : Metadata = {
  title: {default: "GlimzySkins" , template:"%s - GlimzySkins"},
  description: "GlimzySkins - Strona z Skrzynkami CS2!"
};

export default function RootLayout({children}:{children: React.ReactNode}) {
  
    return (
      <html lang="pl">
        <body>
          {children}
        </body>
      </html>
    );

}