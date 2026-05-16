#!/usr/bin/env python3
"""V2: Konservativ template build.

Strategiya:
- Asl matnda ko'p run'larga bo'lingan ma'lumotlar uchun:
  * BIRINCHI run'da to'liq placeholder qo'yiladi (`{{key}}`)
  * Qolgan run'lar BO'SH `''` qilinadi
- Run'lar va ulardagi formatlash (shrift, rang, hajm) saqlanadi
- Order longer-first (ya'ni "012880 " > "012880") — duplikat almashishni oldini olish
"""
import os
import re
import shutil
import zipfile

SRC_DIR = "/Users/mehriddin_xalilov/Documents/MyApp/yem_dokoni/backend/docs"
OUT_DIR = "/Users/mehriddin_xalilov/Documents/MyApp/yem_dokoni/backend/storage/app/private/templates"
WORK_DIR = "/tmp/doc2/build"

os.makedirs(OUT_DIR, exist_ok=True)
if os.path.exists(WORK_DIR):
    shutil.rmtree(WORK_DIR)
os.makedirs(WORK_DIR)


def replace_wt(xml: str, replacements: list[tuple[str, str]]) -> str:
    """Faqat `<w:t [attrs]>EXACT</w:t>` ni `<w:t [attrs]>NEW</w:t>` ga almashtir."""
    # Sort longer-first
    sorted_repl = sorted(replacements, key=lambda r: -len(r[0]))
    for old, new in sorted_repl:
        pattern = re.compile(r'(<w:t\b[^>]*>)' + re.escape(old) + r'(</w:t>)')
        xml = pattern.sub(lambda m: m.group(1) + new + m.group(2), xml)
    return xml


def build(src_filename: str, out_filename: str, replacements: list[tuple[str, str]]):
    src_path = os.path.join(SRC_DIR, src_filename)
    extract_dir = os.path.join(WORK_DIR, out_filename.replace('.docx', ''))
    out_path = os.path.join(OUT_DIR, out_filename)

    with zipfile.ZipFile(src_path) as zf:
        zf.extractall(extract_dir)

    doc_path = os.path.join(extract_dir, 'word', 'document.xml')
    with open(doc_path, 'r', encoding='utf-8') as f:
        xml = f.read()
    new_xml = replace_wt(xml, replacements)
    with open(doc_path, 'w', encoding='utf-8') as f:
        f.write(new_xml)

    if os.path.exists(out_path):
        os.remove(out_path)

    with zipfile.ZipFile(out_path, 'w', zipfile.ZIP_DEFLATED) as zout:
        for root, _, files in os.walk(extract_dir):
            for file in files:
                abs_path = os.path.join(root, file)
                arc_path = os.path.relpath(abs_path, extract_dir)
                zout.write(abs_path, arc_path)

    print(f"✓ {out_filename}")


# ============================================================
# SERTIFIKAT
# ============================================================
sert_replacements = [
    # === RAQAM (2 sahifa: oddiy va trailing-space variant) ===
    ("QV", "{{seria}}"),
    ("012880 ", "{{raqam}} "),     # longer-first
    ("012880", "{{raqam}}"),

    # === F.I.O. (sahifa 1, multi-run: birinchiga placeholder, qolganini bo'shat) ===
    ("TAYMURODOV", "{{familiya_uz}}"),
    ("QUVONDIQ", "{{ism_uz}}"),
    ("MUROD", "{{otasi_ismi_uz}}"),
    ("O‘G‘LI", ""),                # bo'sh — qolgan qism placeholder ichida

    # === TUMAN (UZ — sahifa 1: butun bitta run) ===
    ("KASBI TUMANI KASBIY KO&apos;NIKMALAR ",
     "{{tuman_uz}} TUMANI KASBIY KO&apos;NIKMALAR "),

    # === TUMAN (sahifa 2 kirill: alohida 'KASBI' run) ===
    ("KASBI", "{{tuman_uz}}"),

    # === SANA + SOAT (bitta run ichida 3 placeholder, UZ Sah-1) ===
    ("10.02.2025 yildan 05.05.2025 yilgacha 360 ",
     "{{boshlanish_sanasi}} yildan {{tugash_sanasi}} yilgacha {{soat}} "),
    ("da 10.02.2025 yildan 05.05.2025 yilgacha 360 ",
     "da {{boshlanish_sanasi}} yildan {{tugash_sanasi}} yilgacha {{soat}} "),

    # === KASB (UZ) ===
    ("Elektrogazpayvandchi", "{{kasb_uz}}"),

    # === EN ===
    ("From 10.02.2025 to 05.05.2025 year 360 hours completed full professional course ",
     "From {{boshlanish_sanasi}} to {{tugash_sanasi}} year {{soat}} hours completed full professional course "),
    ("Electric gas ", "{{kasb_en}}"),  # placeholder oxirida space yo'q (qiymat o'zi to'liq)
    ("welder", ""),
    ("In KASBI DISTRICT PROFESSIONAL SKILLS ",
     "In {{tuman_en}} DISTRICT PROFESSIONAL SKILLS "),

    # === RU ===
    ("Электрогазосварщик", "{{kasb_ru}}"),
    ("с 10.02.2025 по 05.05.2025 года ",
     "с {{boshlanish_sanasi}} по {{tugash_sanasi}} года "),
    ("квалификации) (360", "квалификации) ({{soat}}"),
    ("КАСБИЙСКОГО", "{{tuman_ru}}"),

    # === DIREKTOR (2 sahifada ham) ===
    ("XONALIYEV UMIDJON ", "{{direktor_fio}}"),
    ("BARNOYEVICH", ""),

    # === REGISTRATSIYA ===
    ("6550", "{{registratsiya_raqami}}"),
    ("05.05.2025", "{{registratsiya_sanasi}}"),
]

build("Taymurodov Quvondiq Sertifikat (2).docx",
      "sertifikat_kasbiy_konikmalar.docx",
      sert_replacements)


# ============================================================
# GUVOHNOMA
# ============================================================
guvoh_replacements = [
    # F.I.O. — sahifa 1 kirill UZ
    ("Жўраев", "{{familiya_oz}}"),
    ("Ойбек Раҳимқулович", "{{ism_oz}} {{otasi_ismi_oz}}"),

    # F.I.O. — sahifa 2 RU
    ("Жураев Ойбек Рахимкулович",
     "{{familiya_ru}} {{ism_ru}} {{otasi_ismi_ru}}"),
    ("Жураев Ойбек ", "{{familiya_ru}} {{ism_ru}} "),
    ("Жураев", "{{familiya_ru}}"),
    ("Ойбек Рахимкулович", "{{ism_ru}} {{otasi_ismi_ru}}"),
    ("Рахимкулович", "{{otasi_ismi_ru}}"),

    # Berilgan joy
    ("Карши", "{{berilgan_joy_ru}}"),
    ("Қарши  ш.", "{{berilgan_joy_oz}} ш."),
    ("Қарши", "{{berilgan_joy_oz}}"),

    # Raqam (012880 ga o'xshash boshlanmasin uchun longer-first)
    ("ГУВОҲНОМА № 0238", "ГУВОҲНОМА № {{raqam_prefix}}"),
    ("СВИДЕТЕЛЬСТВО № 0238", "СВИДЕТЕЛЬСТВО № {{raqam_prefix}}"),

    # Mutaxassislik
    ("Технологик кувурларни ", "{{mutaxassislik_oz}} "),
    ("ўрнатувчи", ""),
    ("Монтажник  технологических трубопроводов", "{{mutaxassislik_ru}}"),
    ("Монтажник технологических трубопроводов ", "{{mutaxassislik_ru}} "),
    ("Монтажник технологических трубопроводов", "{{mutaxassislik_ru}}"),

    # Razryad
    ("5-разряда", "{{razryad}}-разряда"),
    ("5 (пятого) разряда", "{{razryad}}-tayifa"),

    # Mansabdor shaxslar
    ("Таймуродов. К.М", "{{komissiya_raisi_fio}}"),
    ("Жумаев.М.Р", "{{komissiya_azosi_fio}}"),
]

build("удостоверение_Монтажник_Т_Т_5_разряда_2023 (2).docx",
      "guvohnoma_nazorat_sifat_talim.docx",
      guvoh_replacements)

print(f"\n✓ Output: {OUT_DIR}")