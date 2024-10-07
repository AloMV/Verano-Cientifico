import { PDFDocument, StandardFonts, rgb } from "pdf-lib"; // Asegúrate de importar lo necesario
import axios from "axios";

export default async function getPdf(user, title, id) {
    const signedText = await getSignedText(id);
    let legend = "Por su invaluable presentación del Proyecto:";
    let text = user;

    const urlPdf = "/pdfs/Template.pdf";
    const existingPdfBytes = await fetch(urlPdf).then((res) =>
        res.arrayBuffer()
    );
    const pdfDoc = await PDFDocument.load(existingPdfBytes);
    const pages = pdfDoc.getPages();
    const firstPage = pages[0];
    const { width, height } = firstPage.getSize();

    const centerX = width / 2;
    let y = height / 2 + 30;

    let font = await pdfDoc.embedFont(StandardFonts.HelveticaBold);
    let fontSize = 20;
    let textWidth = font.widthOfTextAtSize(text, fontSize);

    let x = centerX - textWidth / 2;
    firstPage.drawText(text, {
        x: x,
        y: y - fontSize / 2, // Ajustar para centrar verticalmente
        maxWidth: 500,
        font: font,
        size: fontSize,
    });

    fontSize = 12;
    font = await pdfDoc.embedFont(StandardFonts.Helvetica);
    textWidth = font.widthOfTextAtSize(legend, fontSize);
    y -= 70;
    x = centerX - textWidth / 2;

    firstPage.drawText(legend, {
        x: x,
        y: y - fontSize / 2, // Ajustar para centrar verticalmente
        maxWidth: 500,
        font: font,
        color: rgb(0.2, 0.255, 0.333),
        size: fontSize,
    });

    drawText(title, font, 12, firstPage, (y -= 16), centerX);
    drawText("En el II Congreso Internacional de Tecnología y Ciencia Aplicada, desarrollado en el Tecnológico", font, 12, firstPage, (y -= 16), centerX);
    drawText("Nacional de México/Centro Nacional de Investigación y Desarrollo Tecnológico, TecNM/CENIDET,", font, 12, firstPage, (y -= 16), centerX);
    drawText("del 22 al 24 de mayo de 2024.", font, 12, firstPage, (y -= 16), centerX);
    drawText("Cuernavaca, Morelos, 24 de mayo de 2024", font, 12, firstPage, (y -= 50), centerX);
    // SERV
    drawText(`Sello Digital:`, font, 10, firstPage, (y -= 100), centerX);
    fontSize = 6;
    y -= 6;
    const lines = splitAndJustifyText(signedText, font, fontSize, 401);

    lines.forEach((line, index) => {
        y -= fontSize + 3; // Espaciado entre líneas
        // Justificación manual
        let lineWidth = font.widthOfTextAtSize(line, fontSize);
        let spaceWidth = (401 - lineWidth) / (line.length - 1);
        let currentX = centerX - lineWidth / 2;

        for (let char of line) {
            firstPage.drawText(char, {
                x: currentX,
                y: y - fontSize / 2,
                font: font,
                size: fontSize,
                color: rgb(0.2, 0.255, 0.333),
                maxWidth: 401,
            });
            currentX += font.widthOfTextAtSize(char, fontSize) + spaceWidth;
        }
    });
    // guardado
    const pdfBytes = await pdfDoc.save();
    // Crear un Blob con los datos del PDF
    const blob = new Blob([pdfBytes], { type: "application/pdf" });
    const url = URL.createObjectURL(blob);
    return url;
}

const getSignedText = async (id) => {
    try {
        const response = await axios.get(route("article.signPdf", id));
        return response.data.signedText;
    } catch (error) {
        console.error("ERROR AXIOS:", error);
        return null;
    }
};

const drawText = (text, font, fontSize, page, y, centerX) => {
    const textWidth = font.widthOfTextAtSize(text, fontSize);
    const x = Math.max(centerX - textWidth / 2, 52);

    page.drawText(text, {
        x: x,
        y: y - fontSize / 2, // Ajustar para centrar verticalmente
        maxWidth: 520,
        font: font,
        color: rgb(0.2, 0.255, 0.333),
        size: fontSize,
    });
};

const splitAndJustifyText = (text, font, fontSize, maxWidth) => {
    const words = text.split(""); // Dividir por caracteres
    let lines = [];
    let currentLine = words[0];

    for (let i = 1; i < words.length; i++) {
        let char = words[i];
        let width = font.widthOfTextAtSize(currentLine + char, fontSize);

        if (width < maxWidth) {
            currentLine += char;
        } else {
            lines.push(currentLine);
            currentLine = char;
        }
    }
    lines.push(currentLine);
    return lines;
};
